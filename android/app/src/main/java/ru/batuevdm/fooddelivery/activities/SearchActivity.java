package ru.batuevdm.fooddelivery.activities;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.SearchView;
import android.view.LayoutInflater;
import android.view.Menu;
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
import ru.batuevdm.fooddelivery.tools.Api;
import ru.batuevdm.fooddelivery.R;
import ru.batuevdm.fooddelivery.tools.Shop;

public class SearchActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_search);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);

        String query = getIntent().getStringExtra("query");
        search(query);
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

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate( R.menu.main, menu);

        MenuItem myActionMenuItem = menu.findItem( R.id.actionSearch);
        SearchView searchView = (SearchView) myActionMenuItem.getActionView();
        searchView.setQuery(getIntent().getStringExtra("query"),true);
        searchView.setFocusable(true);
        searchView.setIconified(false);
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                if (!query.isEmpty()) {
                    search(query);
                }
                return false;
            }
            @Override
            public boolean onQueryTextChange(String s) {
                // UserFeedback.show( "SearchOnQueryTextChanged: " + s);
                return false;
            }
        });
        return super.onCreateOptionsMenu(menu);
    }

    public void search(String query)
    {
        TextView tv = findViewById(R.id.searchText);
        tv.setText("");
        setTitle(query);

        ProgressBar progressBar = findViewById(R.id.loadProgressSearch);
        Api api = new Api(getApplicationContext());
        api.loading(true, progressBar);

        api.search(query, new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                runOnUiThread(() -> {
                    api.loading(false, progressBar);

                    api.loadError(findViewById(android.R.id.content), v -> {
                        search(query);
                        api.dismissLoadError();
                    });
                });
            }

            @SuppressLint("SetTextI18n")
            @Override
            public void onResponse(Call call, Response response) throws IOException {
                runOnUiThread(() -> {
                    api.loading(false, progressBar);
                    try {
                        String res = response.body() != null ? response.body().string() : "{}";
                        try {
                            JSONObject result = new JSONObject(res);
                            JSONArray products = result.getJSONArray("products");
                            LinearLayout scroll = findViewById(R.id.searchProductsLayout);

                            scroll.removeAllViewsInLayout();

                            if (products.length() > 0) {
                                for (int i = 0; i < products.length(); i++) {
                                    JSONObject product = products.getJSONObject(i);
                                    newProduct(product, scroll);
                                }
                            } else {
                                tv.setText("По запросу \"" + query + "\" ничего не найдено");
                            }
                        } catch (JSONException e) {
                            api.loadError(findViewById(android.R.id.content), v -> {
                                search(query);
                                api.dismissLoadError();
                            });
                        }

                    } catch (IOException e) {
                        api.loadError(findViewById(android.R.id.content), v -> {
                            search(query);
                            api.dismissLoadError();
                        });
                    }
                });
            }
        });
    }

    @SuppressLint("SetTextI18n")
    public void newProduct(JSONObject product, LinearLayout layout)
    {
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
                productPrice.setPaintFlags(productPrice.getPaintFlags()| Paint.STRIKE_THRU_TEXT_FLAG);
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
}
