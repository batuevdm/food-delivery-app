package ru.batuevdm.fooddelivery.activities;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.text.Editable;
import android.text.InputFilter;
import android.text.TextWatcher;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.squareup.picasso.Picasso;

import net.yslibrary.android.keyboardvisibilityevent.KeyboardVisibilityEvent;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;
import ru.batuevdm.fooddelivery.R;
import ru.batuevdm.fooddelivery.base.BaseToolBarActivity;
import ru.batuevdm.fooddelivery.base.InputFilterMinMax;
import ru.batuevdm.fooddelivery.tools.Api;
import ru.batuevdm.fooddelivery.tools.Shop;

public class CartActivity extends BaseToolBarActivity {

    Api api;
    Shop shop;
    int allPrice = 0;

    @Override
    protected int getLayoutId() {
        return R.layout.activity_cart;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setTitle("Корзина");
        api = new Api(getApplicationContext());
        loadCart();
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

    @SuppressLint("SetTextI18n")
    public void loadCart() {
        allPrice = 0;
        shop = new Shop(getApplicationContext());
        JSONArray cart = shop.getCart();

        ProgressBar progressBar = findViewById(R.id.cartProgressBar);
        Button cartButton = findViewById(R.id.cartOrderButton);
        LinearLayout scroll = findViewById(R.id.cartScrollLayout);

        api.loading(true, progressBar);
//        scroll.removeAllViewsInLayout();
        TextView allPriceText = findViewById(R.id.cartAllPRice);

        api.checkCart(cart, new Callback() {
            @Override
            public void onFailure(@NonNull Call call, @NonNull IOException e) {
                runOnUiThread(() -> {
                    api.loading(false, progressBar);

                    api.loadError(findViewById(android.R.id.content), v -> {
                        loadCart();
                        api.dismissLoadError();
                    });
                });
            }

            @Override
            public void onResponse(@NonNull Call call, @NonNull Response response) {
                runOnUiThread(() -> {
                    scroll.removeAllViews();
                    allPrice = 0;
                    api.loading(false, progressBar);
                    try {
                        String res = response.body() != null ? response.body().string() : "{}";
                        JSONArray result = new JSONArray(res);

                        if (result.length() > 0) {
                            for (int i = 0; i < result.length(); i++) {
                                JSONObject product = result.getJSONObject(i);
                                addProduct(product, scroll);
                            }
                            allPriceText.setVisibility(TextView.VISIBLE);
                            cartButton.setVisibility(Button.VISIBLE);

                            cartButton.setOnClickListener(v -> {
                                Intent intent = new Intent(CartActivity.this, OrderActivity.class);
                                startActivity(intent);
                            });
                        } else {
                            allPriceText.setVisibility(TextView.INVISIBLE);
                            cartButton.setVisibility(Button.INVISIBLE);
                            Toast.makeText(getApplicationContext(), "Здесь пока ничего нет", Toast.LENGTH_LONG).show();
                        }

                    } catch (Exception e) {
                        Log.e("bugs_testing", e.getMessage());
                        api.loadError(findViewById(android.R.id.content), v -> {
                            loadCart();
                            api.dismissLoadError();
                        });
                    }
                });
            }
        });
    }

    @SuppressLint("SetTextI18n")
    public void addProduct(JSONObject JSONproduct, LinearLayout layout) throws
            JSONException {
        int productID = JSONproduct.getInt("id");
        JSONObject product = JSONproduct.getJSONObject("product");
        LayoutInflater inflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        @SuppressLint("InflateParams") View rowView = inflater.inflate(R.layout.cart_item, null);
        layout.addView(rowView, layout.getChildCount());

        TextView productName = rowView.findViewById(R.id.cartProductName);
        ImageView productImage = rowView.findViewById(R.id.cartProductImage);
        TextView productPrice = rowView.findViewById(R.id.cartPrice);
        EditText productCol = rowView.findViewById(R.id.cartCol);
        TextView maxProductCol = rowView.findViewById(R.id.cartColMax);
        ImageView deleteProduct = rowView.findViewById(R.id.cartDelete);

        int col = Integer.parseInt(product.getString("col"));
        int cartCol = JSONproduct.getInt("col");
        if (col < cartCol) {
            cartCol = col;
        }
        productCol.setText(String.format("%s", cartCol));
        productCol.setFilters(new InputFilter[]{new InputFilterMinMax(1, col)});
        maxProductCol.setText("Максимальное количество: " + col);

        productName.setText(product.getString("name"));
        productPrice.setText(product.getString("price") + " \u20BD");

        if (product.getString("new_price").equals("null")) {
            productPrice.setText(product.getString("price") + " \u20BD");
            allPrice += Integer.parseInt(product.getString("price")) * cartCol;
        } else {
            productPrice.setText(product.getString("new_price") + " \u20BD");
            allPrice += Integer.parseInt(product.getString("new_price")) * cartCol;
        }

        TextView allPriceText = findViewById(R.id.cartAllPRice);
        allPriceText.setText("Итого: " + allPrice + " \u20BD");

        String photo = product.getString("main_photo").equals("null") ? "default.png" : product.getString("main_photo");
        Picasso.get()
                .load(Api.site + "images/products/" + photo)
                .placeholder(R.drawable.ic_menu_gallery)
                .error(R.drawable.ic_menu_camera)
                .into(productImage);

        deleteProduct.setOnClickListener(v -> {
            shop.deleteFromCart(productID);
            loadCart();
        });

        productCol.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }

            @Override
            public void afterTextChanged(Editable s) {
                if (!s.toString().isEmpty()) {
                    shop.changeCol(productID, Integer.parseInt(s.toString()));
                }
            }
        });

        productCol.setOnFocusChangeListener((v, hasFocus) -> {
            if (!hasFocus) {
                loadCart();
            }
        });

        productCol.setOnKeyListener((v, keyCode, event) -> {
            if ((event.getAction() == KeyEvent.ACTION_DOWN) &&
                    (keyCode == KeyEvent.KEYCODE_ENTER)) {
                try {
                    if (v.hasFocus())
                        v.clearFocus();
                    loadCart();
                } catch (Exception ignored) {
                }
            }
            return false;
        });

        KeyboardVisibilityEvent.setEventListener(CartActivity.this, isOpen -> {
            if (!isOpen) {
                loadCart();
            }
        });
    }

}
