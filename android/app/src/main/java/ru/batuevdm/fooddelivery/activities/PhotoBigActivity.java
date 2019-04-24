package ru.batuevdm.fooddelivery.activities;

import android.os.Bundle;
import android.view.MenuItem;
import android.widget.ImageView;

import com.squareup.picasso.Picasso;

import ru.batuevdm.fooddelivery.R;
import ru.batuevdm.fooddelivery.base.BaseToolBarActivity;

public class PhotoBigActivity extends BaseToolBarActivity {

    @Override
    protected int getLayoutId() {
        return R.layout.activity_photo_big;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        ImageView image = findViewById(R.id.photoBig);

        String imageURL = getIntent().getStringExtra("image");
        Picasso.get()
                .load(imageURL)
                .placeholder(R.drawable.ic_menu_gallery)
                .error(R.drawable.ic_menu_camera)
                .into(image);

        setTitle("Просмотр изображения");

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
