package com.example.smartstore1.activities.main;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import androidx.appcompat.app.AppCompatActivity;
import androidx.preference.PreferenceManager;
import com.example.smartstore1.R;
import com.example.smartstore1.activities.auth.LoginActivity;
import com.example.smartstore1.utils.helpers.SharedPrefManager;
import com.google.firebase.auth.FirebaseAuth;

public class SettingsActivity extends AppCompatActivity {
    private Button companyProfileButton;
    private Button logoutButton;
    private SharedPrefManager prefManager;
    private FirebaseAuth mAuth;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_settings);

        prefManager = SharedPrefManager.getInstance(this);
        mAuth = FirebaseAuth.getInstance();

        initializeViews();
        setupListeners();
    }

    private void initializeViews() {
        companyProfileButton = findViewById(R.id.companyProfileButton);
        logoutButton = findViewById(R.id.logoutButton);
    }

    private void setupListeners() {
        companyProfileButton.setOnClickListener(v -> 
            startActivity(new Intent(this, CompanyProfileActivity.class)));

        logoutButton.setOnClickListener(v -> logout());
    }

    private void logout() {
        // Sign out from Firebase
        mAuth.signOut();
        
        // Clear preferences
        prefManager.clear();
        
        // Return to login screen
        Intent intent = new Intent(this, LoginActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intent);
        finish();
    }
}
