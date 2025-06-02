package com.example.smartstore1;

import android.app.Application;
import com.google.firebase.FirebaseApp;

public class SmartStoreApplication extends Application {
    @Override
    public void onCreate() {
        super.onCreate();
        FirebaseApp.initializeApp(this);
    }
}
