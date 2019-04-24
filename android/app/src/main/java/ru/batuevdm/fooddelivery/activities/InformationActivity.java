package ru.batuevdm.fooddelivery.activities;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;

import ru.batuevdm.fooddelivery.R;
import ru.batuevdm.fooddelivery.base.BaseToolBarActivity;

public class InformationActivity extends BaseToolBarActivity {

    @Override
    protected int getLayoutId() {
        return R.layout.activity_information;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setTitle("О приложении");
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
}
