package ru.batuevdm.fooddelivery.base;

import android.app.Application;

import com.gw.swipeback.tools.WxSwipeBackActivityManager;

public class MainApplication extends Application {
    @Override
    public void onCreate() {
        super.onCreate();
        WxSwipeBackActivityManager.getInstance().init(this);
    }
}