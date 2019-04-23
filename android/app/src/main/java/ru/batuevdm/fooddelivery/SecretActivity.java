package ru.batuevdm.fooddelivery;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;

public class SecretActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        View decorView = getWindow().getDecorView();
        int uiOptions = View.SYSTEM_UI_FLAG_FULLSCREEN;
        decorView.setSystemUiVisibility(uiOptions);

        setContentView(R.layout.activity_secret);

        getSupportActionBar().hide();
    }
}
