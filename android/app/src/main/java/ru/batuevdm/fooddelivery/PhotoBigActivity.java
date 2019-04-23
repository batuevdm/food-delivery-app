package ru.batuevdm.fooddelivery;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.MenuItem;
import android.widget.ImageView;

import com.squareup.picasso.Picasso;

public class PhotoBigActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_photo_big);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowHomeEnabled(true);

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
