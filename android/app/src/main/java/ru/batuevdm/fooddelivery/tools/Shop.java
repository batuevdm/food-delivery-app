package ru.batuevdm.fooddelivery.tools;

import android.content.Context;
import android.content.SharedPreferences;
import android.support.design.widget.Snackbar;
import android.view.View;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class Shop {
    private Context context;
    private SharedPreferences storage;
    private static final String APP_PREFERENCES_CART = "cart";

    public Shop(Context context) {
        this.context = context;
        storage = context.getSharedPreferences(APP_PREFERENCES_CART, Context.MODE_PRIVATE);
    }

    private boolean saveSettings(String name, String value) {
        SharedPreferences.Editor editor = storage.edit();
        editor.putString(name, value);
        return editor.commit();
    }

    private String getSettings(String name) {
        if (storage.contains(name)) {
            return storage.getString(name, "");
        }
        return "";
    }

    public void addToCart(int productID, int col, int productsCol, View v) {
        String oldProducts = getSettings("cart");
        if (oldProducts.trim().isEmpty()) {
            oldProducts = "{\"cart\": []}";
        }
        try {
            JSONObject oldCartObject = new JSONObject(oldProducts);
            JSONArray oldCart = oldCartObject.getJSONArray("cart");
            JSONObject newCartObject = new JSONObject();
            JSONArray newCart = new JSONArray();

            boolean isOld = false;
            for (int i = 0; i < oldCart.length(); i++) {
                JSONObject oldProduct = oldCart.getJSONObject(i);
                int _productID = oldProduct.getInt("id");
                int _col = oldProduct.getInt("col");
                if (_productID == productID) {
                    isOld = true;
                    if (col <= productsCol && (_col + col) <= productsCol) {
                        _col += col;
                    } else {
                        // Product col < Cart product col
                        Toast.makeText(context, "Количество товаров больше, чем есть в наличии", Toast.LENGTH_LONG).show();
                        return;
                    }
                }
                JSONObject newProduct = new JSONObject();
                newProduct.put("id", _productID);
                newProduct.put("col", _col);

                newCart.put(newProduct);
            }

            if (!isOld) {
                JSONObject newProduct = new JSONObject();
                newProduct.put("id", productID);
                newProduct.put("col", col);

                newCart.put(newProduct);
            }

            newCartObject.put("cart", newCart);

            String newProducts = newCartObject.toString();
            if (saveSettings("cart", newProducts))
                Snackbar.make(v, "Товар добавлен в корзину", Snackbar.LENGTH_LONG).show();
            else
                Toast.makeText(context, "Ошибка", Toast.LENGTH_LONG).show();
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    public boolean deleteFromCart(int productID) {
        String oldProducts = getSettings("cart");
        if (oldProducts.trim().isEmpty()) {
            oldProducts = "{\"cart\": []}";
        }
        try {
            JSONObject oldCartObject = new JSONObject(oldProducts);
            JSONArray oldCart = oldCartObject.getJSONArray("cart");
            JSONObject newCartObject = new JSONObject();
            JSONArray newCart = new JSONArray();

            for (int i = 0; i < oldCart.length(); i++) {
                JSONObject oldProduct = oldCart.getJSONObject(i);
                int _productID = oldProduct.getInt("id");
                int _col = oldProduct.getInt("col");
                if (_productID != productID) {
                    JSONObject newProduct = new JSONObject();
                    newProduct.put("id", _productID);
                    newProduct.put("col", _col);
                    newCart.put(newProduct);
                }

            }

            newCartObject.put("cart", newCart);

            String newProducts = newCartObject.toString();
            return saveSettings("cart", newProducts);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return false;
    }

    public boolean changeCol(int productID, int newCol) {
        String oldProducts = getSettings("cart");
        if (oldProducts.trim().isEmpty()) {
            oldProducts = "{\"cart\": []}";
        }
        try {
            JSONObject oldCartObject = new JSONObject(oldProducts);
            JSONArray oldCart = oldCartObject.getJSONArray("cart");
            JSONObject newCartObject = new JSONObject();
            JSONArray newCart = new JSONArray();

            for (int i = 0; i < oldCart.length(); i++) {
                JSONObject oldProduct = oldCart.getJSONObject(i);
                int _productID = oldProduct.getInt("id");
                int _col = oldProduct.getInt("col");
                if (_productID == productID) {
                    _col = newCol;
                }
                JSONObject newProduct = new JSONObject();
                newProduct.put("id", _productID);
                newProduct.put("col", _col);

                newCart.put(newProduct);
            }

            newCartObject.put("cart", newCart);

            String newProducts = newCartObject.toString();
            return saveSettings("cart", newProducts);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return false;
    }

    public JSONArray getCart() {
        try {
            String cartString = getSettings("cart");
            if (cartString.trim().isEmpty()) {
                cartString = "{\"cart\": []}";
            }

            JSONObject cartObject = new JSONObject(cartString);

            return cartObject.getJSONArray("cart");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return new JSONArray();
    }

    public void clearCart() {
        saveSettings("cart", "");
    }
}
