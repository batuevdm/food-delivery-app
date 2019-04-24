package ru.batuevdm.fooddelivery.activities;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.os.Bundle;
import android.support.design.widget.NavigationView;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.SearchView;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;
import ru.batuevdm.fooddelivery.R;
import ru.batuevdm.fooddelivery.tools.Api;
import ru.batuevdm.fooddelivery.tools.Shop;

public class MainActivity extends AppCompatActivity
        implements NavigationView.OnNavigationItemSelectedListener {

    Button btn1;
    int clicks = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        DrawerLayout drawer = findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        drawer.addDrawerListener(toggle);
        toggle.syncState();

        NavigationView navigationView = findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);

        loadProducts();
    }

    public void onClickSecret(View view)
    {
        if (clicks >= 4) {
            Toast.makeText(this, "\uD83D\uDD25\uD83D\uDD25\uD83D\uDD25\uD83D\uDD25\uD83D\uDD25\uD83D\uDD25\uD83D\uDD25\uD83D\uDD25\uD83D\uDD25", Toast.LENGTH_LONG).show();
            Intent intent = new Intent(MainActivity.this, SecretActivity.class);
            startActivity(intent);
            clicks = -1;
        }
        clicks++;
    }

    @Override
    public void onBackPressed() {
        DrawerLayout drawer = findViewById(R.id.drawer_layout);
        if (drawer.isDrawerOpen(GravityCompat.START)) {
            drawer.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate( R.menu.main, menu);

        MenuItem myActionMenuItem = menu.findItem( R.id.actionSearch);
        SearchView searchView = (SearchView) myActionMenuItem.getActionView();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                if (!query.isEmpty()) {

                    Intent intent = new Intent(MainActivity.this, SearchActivity.class);
                    intent.putExtra("query", query);
                    startActivity(intent);

                }
                return false;
            }
            @Override
            public boolean onQueryTextChange(String s) {
                return false;
            }
        });
        return super.onCreateOptionsMenu(menu);
    }

    @SuppressWarnings("StatementWithEmptyBody")
    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        int id = item.getItemId();

        Intent intent;
        switch (id) {
            case R.id.menuInformation:
                intent = new Intent(this, InformationActivity.class);
                startActivity(intent);
                break;

            case R.id.menuCategories:
                intent = new Intent(this, CategoriesActivity.class);
                startActivity(intent);
                break;

            case R.id.menuCart:
                intent = new Intent(this, CartActivity.class);
                startActivity(intent);
                break;

        }

        DrawerLayout drawer = findViewById(R.id.drawer_layout);
        drawer.closeDrawer(GravityCompat.START);
        return true;
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

    public void loadProducts()
    {
        ProgressBar progressBar = findViewById(R.id.loadProgress);
        Api api = new Api(getApplicationContext());
        api.loading(true, progressBar);
        api.getNewProducts(new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                runOnUiThread(() -> {
                    api.loading(false, progressBar);

                    api.loadError(findViewById(android.R.id.content), v -> {
                        loadProducts();
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
                            LinearLayout scroll = findViewById(R.id.newProductsLayout);

                            for (int i = 0; i < products.length(); i++) {
                                JSONObject product = products.getJSONObject(i);
                                newProduct(product, scroll);
                            }
                        } catch (JSONException e) {
                            api.loadError(findViewById(android.R.id.content), v -> {
                                loadProducts();
                                api.dismissLoadError();
                            });
                        }

                    } catch (IOException e) {
                        api.loadError(findViewById(android.R.id.content), v -> {
                            loadProducts();
                            api.dismissLoadError();
                        });
                    }
                });
            }
        });
    }

}
