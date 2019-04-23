package ru.batuevdm.fooddelivery;
// Подключение библиотек

import android.content.Context;
import android.support.design.widget.Snackbar;
import android.util.Log;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.Toast;

import org.json.JSONArray;

import java.util.HashMap;
import java.util.Map;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.MultipartBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;

// Класс для работы с API сайта
class Api {
    static String site = "https://food.batuevdm.ru/"; // Адрес сайта
    private Context context; // Контекст приложения для показа уведомлений
    private Snackbar bar; // Переменная Snackbar (Сообщение внизу экрана)

    /**
     * Конструктор класса
     *
     * @param context Контекст приложения
     */
    Api(Context context) {
        this.context = context;
    }

    /**
     * GET-запрос к сайту
     *
     * @param address  Адрес сайта
     * @param callback Callback функция, которая выполнится при завершении запроса
     */
    private void get(String address, Callback callback) {
        OkHttpClient client = new OkHttpClient();
        Request request = new Request.Builder()
                .url(site + address)
                .build();

        Call call = client.newCall(request);
        call.enqueue(callback);
        Log.d("test_uri", "Address: " + site + address);
    }

    /**
     * GET-запрос к сайту
     *
     * @param address  Адрес сайта
     * @param params   Параметры запроса
     * @param callback Callback функция, которая выполнится при завершении запроса
     */
    private void post(String address, Map<String, String> params, Callback callback) {
        OkHttpClient client = new OkHttpClient();
        MultipartBody.Builder requestBuilder = new MultipartBody.Builder()
                .setType(MultipartBody.FORM);
        for (Map.Entry<String, String> entry : params.entrySet()) {
            requestBuilder.addFormDataPart(entry.getKey(), entry.getValue());
        }

        RequestBody requestBody = requestBuilder.build();

        Request request = new Request.Builder()
                .post(requestBody)
                .url(site + address)
                .build();

        Call call = client.newCall(request);
        call.enqueue(callback);
        Log.d("test_uri", "Address [POST]: " + site + address);
    }

    /**
     * Вывод сообщения на экран
     *
     * @param text Текст сообщения
     */
    void toast(String text) {
        Toast toast = Toast.makeText(context, text, Toast.LENGTH_SHORT);
        toast.show();
    }

    /**
     * Показ или скрытие прогресс бара
     *
     * @param status      Показать (true) или скрыть (false) прогресс-бар
     * @param progressBar Прогресс-бар
     */
    void loading(boolean status, ProgressBar progressBar) {
        if (status) {
            progressBar.setVisibility(ProgressBar.VISIBLE);
        } else {
            progressBar.setVisibility(ProgressBar.INVISIBLE);
        }
    }

    /**
     * Вывод сообщения при ошибке загрузки с кнопкой повтора соединения
     *
     * @param view            Форма, на которой покажется сообщение
     * @param onClickListener Обработчик клика на кнопку повтора соединения
     */
    void loadError(View view, View.OnClickListener onClickListener) {
        bar = Snackbar.make(view, "Ошибка загрузки. Проверьте подключение к интернету.", Snackbar.LENGTH_INDEFINITE);
        bar.setAction("Повторить", onClickListener);
        bar.show();
    }

    /**
     * Скрытие ошибки загрузки
     */
    void dismissLoadError() {
        if (bar != null && bar.isShown())
            bar.dismiss();
    }

    /**
     * Получение новых продуктов
     *
     * @param callback Функция, которая выполнится при завершении запроса
     */
    void getNewProducts(Callback callback) {
        get("api/products/get_new", callback);
    }

    /**
     * Получение товара по его ID
     *
     * @param productID ID товара
     * @param callback  Функция, которая выполнится при завершении запроса
     */
    void getProduct(int productID, Callback callback) {
        get("api/products/get/" + productID, callback);
    }

    /**
     * Получение дочерних категорий
     *
     * @param parentCategory ID родительской категории (-1 если нужно получить основные)
     * @param callback       Функция, которая выполнится при завершении запроса
     */
    void getCategories(int parentCategory, Callback callback) {
        if (parentCategory == -1) {
            get("api/categories/get", callback);
        } else {
            get("api/categories/one/" + parentCategory, callback);
        }
    }

    /**
     * Получение товаров категории
     *
     * @param categoryID ID категории
     * @param callback   Функция, которая выполнится при завершении запроса
     */
    void getCategoryProducts(int categoryID, Callback callback) {
        get("api/products/get_category/" + categoryID, callback);
    }

    /**
     * Поиск товаров
     *
     * @param query    Строка запроса
     * @param callback Функция, которая выполнится при завершении запроса
     */
    void search(String query, Callback callback) {
        get("api/products/search?q=" + query, callback);
    }

    /**
     * Оформление нового заказа
     *
     * @param name     Имя покупателя
     * @param phone    Номер телефона покупателя
     * @param cart     Товары из корзины
     * @param callback Функция, которая выполнится при завершении запроса
     */
    void order(String name, String phone, JSONArray cart, Callback callback) {
        Map<String, String> params = new HashMap<>();
        params.put("name", name);
        params.put("phone", phone);
        params.put("cart", cart.toString());

        post("api/orders/new", params, callback);
    }

    void checkCart(JSONArray cart, Callback callback) {
        Map<String, String> params = new HashMap<>();
        params.put("cart", cart.toString());

        post("api/cart/check", params, callback);
    }
}