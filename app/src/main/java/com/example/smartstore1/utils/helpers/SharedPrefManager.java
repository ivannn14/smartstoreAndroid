package com.example.smartstore1.utils.helpers;

import android.content.Context;
import android.content.SharedPreferences;

public class SharedPrefManager {
    private static final String PREF_NAME = "SmartStorePrefs";
    private static SharedPrefManager instance;
    private final SharedPreferences prefs;

    private SharedPrefManager(Context context) {
        prefs = context.getApplicationContext()
                      .getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
    }

    public static synchronized SharedPrefManager getInstance(Context context) {
        if (instance == null) {
            instance = new SharedPrefManager(context);
        }
        return instance;
    }

    public void saveString(String key, String value) {
        prefs.edit().putString(key, value).apply();
    }

    public void saveString(String key, String value, boolean commit) {
        if (commit) {
            prefs.edit().putString(key, value).commit();
        } else {
            saveString(key, value);
        }
    }

    public String getString(String key) {
        return prefs.getString(key, "");
    }

    public void saveBoolean(String key, boolean value) {
        prefs.edit().putBoolean(key, value).apply();
    }

    public void saveBoolean(String key, boolean value, boolean commit) {
        if (commit) {
            prefs.edit().putBoolean(key, value).commit();
        } else {
            saveBoolean(key, value);
        }
    }

    public boolean getBoolean(String key) {
        return prefs.getBoolean(key, false);
    }

    public void clear() {
        prefs.edit().clear().commit(); // Always use commit for clearing
    }
    
    public boolean isLoggedIn() {
        return getBoolean("user_logged_in");
    }

    public String getUserId() {
        return getString("user_id");
    }

    public String getUserEmail() {
        return getString("user_email");
    }
}
