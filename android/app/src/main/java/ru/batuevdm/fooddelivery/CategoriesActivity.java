package ru.batuevdm.fooddelivery;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

public class CategoriesActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_categories);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);


        int parentCategoryID = getIntent().getIntExtra("category", -1);
        loadCategories(parentCategoryID);

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    public void loadCategories(int categoryID) {
        Api api = new Api(getApplicationContext());
        ProgressBar progressBar = findViewById(R.id.categoriesProgressBar);

        api.loading(true, progressBar);
        api.getCategories(categoryID, new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                runOnUiThread(() -> {
                    api.loading(false, progressBar);

                    api.loadError(findViewById(android.R.id.content), v -> {
                        loadCategories(categoryID);
                        api.dismissLoadError();
                    });
                });
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                runOnUiThread(() -> {
                    try {
                        String res = response.body() != null ? response.body().string() : "{}";
                        try {
                            LinearLayout scroll = findViewById(R.id.categoriesScrollLayout);
                            JSONObject result = new JSONObject(res);
                            if (categoryID > 0) {
                                // Subcategories
                                JSONArray subcategories = result.getJSONArray("subcategories");
                                JSONObject category = result.getJSONObject("category");

                                if (subcategories.length() == 0) {
                                    // Products
                                    loadProducts(Integer.parseInt(category.getString("id")));
                                } else {
                                    api.loading(false, progressBar);
                                    for (int i = 0; i < subcategories.length(); i++) {
                                        JSONObject categoryItem = subcategories.getJSONObject(i);
                                        newCategory(categoryItem, scroll);
                                    }
                                }

                                setTitle(category.getString("name"));
                            } else {
                                // Main Categories
                                api.loading(false, progressBar);
                                JSONArray categories = result.getJSONArray("categories");
                                for (int i = 0; i < categories.length(); i++) {
                                    JSONObject categoryItem = categories.getJSONObject(i);
                                    if (categoryItem.getString("parent").equals("null"))
                                        newCategory(categoryItem, scroll);
                                }

                                setTitle("Категории");
                            }
                        } catch (JSONException e) {
                            api.loading(false, progressBar);
                            api.loadError(findViewById(android.R.id.content), v -> {
                                loadCategories(categoryID);
                                api.dismissLoadError();
                            });
                        }

                    } catch (IOException e) {
                        api.loading(false, progressBar);
                        api.loadError(findViewById(android.R.id.content), v -> {
                            loadCategories(categoryID);
                            api.dismissLoadError();
                        });
                    }
                });
            }
        });
    }

    public void newCategory(JSONObject category, LinearLayout layout) {
        try {
            LayoutInflater inflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            View rowView = inflater.inflate(R.layout.category_item, null);
            layout.addView(rowView, layout.getChildCount());

            TextView categoryName = rowView.findViewById(R.id.categoryName);
            ImageView categoryImage = rowView.findViewById(R.id.categoryImage);

            categoryName.setText(category.getString("name"));
            String photo = category.getString("image").equals("null") ? "default.png" : category.getString("image");

            Picasso.get()
                    .load(Api.site + "images/products/" + photo)
                    .placeholder(R.drawable.ic_menu_gallery)
                    .error(R.drawable.ic_menu_camera)
                    .into(categoryImage);

            rowView.setOnClickListener(v -> {
                Intent intent = new Intent(this, CategoriesActivity.class);
                try {
                    intent.putExtra("category", Integer.parseInt(category.getString("id")));
                    startActivity(intent);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            });
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @SuppressLint("SetTextI18n")
    public void newProduct(JSONObject product, LinearLayout layout) {
        LayoutInflater inflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        try {
            View rowView = inflater.inflate(R.layout.product_item, null);
            layout.addView(rowView, layout.getChildCount());

            TextView productName = rowView.findViewById(R.id.productName);
            ImageView productImage = rowView.findViewById(R.id.productImage);
            TextView productPrice = rowView.findViewById(R.id.productPrice);
            TextView productNewPrice = rowView.findViewById(R.id.productNewPrice);
            Button productAddToCart = rowView.findViewById(R.id.productAddToCart);

            productName.setText(product.getString("name"));
            productPrice.setText(product.getString("price") + " \u20BD");
            if (product.getString("new_price").equals("null")) {
                productNewPrice.setVisibility(TextView.INVISIBLE);
            } else {
                productNewPrice.setText(product.getString("new_price") + " \u20BD");
                productPrice.setPaintFlags(productPrice.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
                productNewPrice.setVisibility(TextView.VISIBLE);
            }
            String photo = product.getString("main_photo").equals("null") ? "default.png" : product.getString("main_photo");
            Picasso.get()
                    .load(Api.site + "images/products/" + photo)
                    .placeholder(R.drawable.ic_menu_gallery)
                    .error(R.drawable.ic_menu_camera)
                    .into(productImage);

            int col = Integer.parseInt(product.getString("col"));
            productAddToCart.setOnClickListener(v -> {
                Shop shop = new Shop(getApplicationContext());
                try {
                    shop.addToCart(Integer.parseInt(product.getString("id")), 1, col, v);
                } catch (Exception e) {
                    e.printStackTrace();
                }
            });

            if (col < 1)
                productAddToCart.setVisibility(Button.INVISIBLE);

            rowView.setOnClickListener(v -> {
                Intent intent = new Intent(this, ProductActivity.class);
                try {
                    intent.putExtra("product_id", product.getString("id"));
                } catch (JSONException e) {
                    intent.putExtra("product_id", -1);
                }
                startActivity(intent);
            });

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void loadProducts(int categoryID) {
        ProgressBar progressBar = findViewById(R.id.categoriesProgressBar);
        Api api = new Api(getApplicationContext());
        api.loading(true, progressBar);
        api.getCategoryProducts(categoryID, new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                runOnUiThread(() -> {
                    api.loading(false, progressBar);

                    api.loadError(findViewById(android.R.id.content), v -> {
                        loadProducts(categoryID);
                        api.dismissLoadError();
                    });
                });
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                runOnUiThread(() -> {
                    api.loading(false, progressBar);
                    try {
                        String res = response.body() != null ? response.body().string() : "{}";
                        try {
                            JSONObject result = new JSONObject(res);
                            JSONArray products = result.getJSONArray("products");
                            LinearLayout scroll = findViewById(R.id.categoriesScrollLayout);

                            for (int i = 0; i < products.length(); i++) {
                                JSONObject product = products.getJSONObject(i);
                                newProduct(product, scroll);
                            }

                            if (products.length() == 0) {
                                Snackbar bar = Snackbar.make(findViewById(android.R.id.content), "В этой категории нет товаров", Snackbar.LENGTH_INDEFINITE);
                                bar.setAction("Назад", v -> {
                                    onBackPressed();
                                });
                                bar.show();
                            }
                        } catch (JSONException e) {
                            api.loadError(findViewById(android.R.id.content), v -> {
                                loadProducts(categoryID);
                                api.dismissLoadError();
                            });
                        }

                    } catch (IOException e) {
                        api.loadError(findViewById(android.R.id.content), v -> {
                            loadProducts(categoryID);
                            api.dismissLoadError();
                        });
                    }
                });
            }
        });
    }
}
