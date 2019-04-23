package ru.batuevdm.fooddelivery;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v4.widget.NestedScrollView;
import android.support.v7.app.AppCompatActivity;
import android.text.Spannable;
import android.text.SpannableStringBuilder;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.HorizontalScrollView;
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

public class ProductActivity extends AppCompatActivity {

    Boolean hideFAB = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_product);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);

        Intent intent = getIntent();
        int productID = Integer.parseInt(intent.getStringExtra("product_id"));
        loadProduct(productID);

        NestedScrollView scrollView = findViewById(R.id.productScrollView);
        scrollView.setOnScrollChangeListener((NestedScrollView.OnScrollChangeListener) (v, scrollX, scrollY, oldScrollX, oldScrollY) -> {
            FloatingActionButton button = findViewById(R.id.mainFabToCart);
            if (hideFAB) return;
            if (scrollY > oldScrollY) {
                button.hide();
            } else {
                button.show();
            }
        });
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

    public void loadProduct(int productID) {
        Api api = new Api(this);
        if (productID > 0) {
            ProgressBar progress = findViewById(R.id.mainPrpgressBar);

            api.loading(true, progress);
            hideFAB = true;
            api.getProduct(
                    productID,
                    new Callback() {
                        @Override
                        public void onFailure(Call call, IOException e) {
                            runOnUiThread(() -> {
                                api.loading(false, progress);

                                api.loadError(findViewById(android.R.id.content), v -> {
                                    loadProduct(productID);
                                    api.dismissLoadError();
                                });
                            });
                        }

                        @SuppressLint({"SetTextI18n"})
                        @Override
                        public void onResponse(Call call, Response response) throws IOException {
                            runOnUiThread(() -> {
                                api.loading(false, progress);
                                hideFAB = false;

                                try {
                                    String res = response.body() != null ? response.body().string() : "{}";
                                    try {
                                        JSONObject result = new JSONObject(res);

                                        String status = result.getString("status");
                                        if (status.equals("error")) {
                                            api.toast(result.getString("message"));
                                            return;
                                        }

                                        JSONObject product = result.getJSONObject("product");

                                        TextView productName =                  findViewById(R.id.mainProductName);
                                        ImageView productImage =                findViewById(R.id.mainProductPhoto);
                                        TextView productPrice =                 findViewById(R.id.mainProductPrice);
                                        TextView productNewPrice =              findViewById(R.id.mainProductNewPrice);
                                        TextView productDescription =           findViewById(R.id.mainProductDescription);
                                        FloatingActionButton productAddToCart = findViewById(R.id.mainFabToCart);
                                        TextView productSpecs =                 findViewById(R.id.mainProductSpecs);
                                        TextView productSpecsTitle =            findViewById(R.id.mainProductSpecsTitle);
                                        TextView productCol =                   findViewById(R.id.mainProductCol);

                                        productAddToCart.setOnClickListener(view -> {
                                            Shop shop = new Shop(getApplicationContext());
                                            try {
                                                shop.addToCart(Integer.parseInt(product.getString("id")), 1, Integer.parseInt(product.getString("col")), findViewById(android.R.id.content));
                                            } catch (Exception e) {
                                                api.toast("Fail");
                                            }
                                        });
                                        productAddToCart.show();

                                        int col = Integer.parseInt(product.getString("col"));
                                        productCol.setText("В наличии: " + col + " шт.");
                                        if (col == 0) {
                                            hideFAB = true;
                                            productAddToCart.hide();
                                            Snackbar bar = Snackbar.make(findViewById(android.R.id.content), "Нет в наличии", Snackbar.LENGTH_INDEFINITE);
                                            bar.setAction("ОК", v -> {
                                                bar.dismiss();
                                            });
                                            bar.show();
                                            productCol.setText("Нет в наличии");
                                        }

                                        productName.setText(product.getString("name"));
                                        productPrice.setText(product.getString("price") + " \u20BD");

                                        String productDescriptionText = product.getString("desc");
                                        productDescription.setText(productDescriptionText);
                                        if (productDescriptionText.trim().isEmpty()) {
                                            productDescription.setVisibility(TextView.INVISIBLE);
                                            productDescription.setHeight(0);
                                        }

                                        if (product.getString("new_price").equals("null")) {
                                            productNewPrice.setVisibility(TextView.INVISIBLE);
                                            productNewPrice.setHeight(0);
                                        } else {
                                            productNewPrice.setText(product.getString("new_price") + " \u20BD");
                                            productPrice.setPaintFlags(productPrice.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
                                            productNewPrice.setVisibility(TextView.VISIBLE);
                                        }

                                        setTitle(product.getString("name"));

                                        String photo = product.getString("main_photo").equals("null") ? "default.png" : product.getString("main_photo");
                                        Picasso.get()
                                                .load(Api.site + "images/products/" + photo)
                                                .placeholder(R.drawable.ic_menu_gallery)
                                                .error(R.drawable.ic_menu_camera)
                                                .into(productImage);

                                        productImage.setOnClickListener(v -> {
                                            Intent intent = new Intent(ProductActivity.this, PhotoBigActivity.class);
                                            intent.putExtra("image", Api.site + "images/products/" + photo);
                                            startActivity(intent);
                                        });

                                        JSONArray photos = product.getJSONArray("photos");
                                        JSONArray specs = product.getJSONArray("specs");
                                        SpannableStringBuilder specsString = new SpannableStringBuilder();

                                        for (int i = 0; i < specs.length(); i++) {
                                            JSONObject spec = specs.getJSONObject(i);

                                            String specName = spec.getString("name");

                                            specsString.append(specName);
                                            specsString.setSpan(new android.text.style.StyleSpan(android.graphics.Typeface.BOLD), 0, specName.length() - 1, Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);
                                            specsString.append(": ");
                                            specsString.append(spec.getString("value"));
                                            specsString.append("\n");
                                        }

                                        String specsText = specsString.toString();
                                        productSpecs.setText(specsText);
                                        productSpecsTitle.setVisibility(TextView.VISIBLE);
                                        if (specsText.trim().isEmpty())
                                            productSpecsTitle.setVisibility(TextView.INVISIBLE);

                                        HorizontalScrollView photosLayout = findViewById(R.id.mainProductPhotos);
                                        ViewGroup.LayoutParams params = photosLayout.getLayoutParams();
                                        if (photos.length() > 0) {
                                            params.height = (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 100, getResources().getDisplayMetrics());
                                        } else {
                                            params.height = 0;
                                        }
                                        photosLayout.setLayoutParams(params);

                                        for (int i = 0; i < photos.length(); i++) {
                                            JSONObject smallPhoto = photos.getJSONObject(i);
                                            String smallPhotoURL = smallPhoto.getString("photo");
                                            addPhoto(Api.site + "images/products/" + smallPhotoURL);
                                        }

                                    } catch (JSONException e) {
                                        api.loadError(findViewById(android.R.id.content), v -> {
                                            loadProduct(productID);
                                            api.dismissLoadError();
                                        });
                                    }

                                } catch (IOException e) {
                                    api.loadError(findViewById(android.R.id.content), v -> {
                                        loadProduct(productID);
                                        api.dismissLoadError();
                                    });
                                }
                            });
                        }
                    }
            );
        } else {
            api.toast("Товар не найден");
        }
    }

    public void addPhoto(String url) {
        LinearLayout layout = findViewById(R.id.mainProductPhotosLayout);
        LayoutInflater inflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);

        View rowView = inflater.inflate(R.layout.product_photo, null);
        layout.addView(rowView, layout.getChildCount());

        ImageView image = rowView.findViewById(R.id.mainProductPhotoSingle);

        Picasso.get()
                .load(url)
                .placeholder(R.drawable.ic_menu_gallery)
                .error(R.drawable.ic_menu_camera)
                .into(image);

        image.setOnClickListener(v -> {
            Intent intent = new Intent(this, PhotoBigActivity.class);
            intent.putExtra("image", url);
            startActivity(intent);
        });

    }
}
