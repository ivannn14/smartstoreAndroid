package com.example.smartstore1.activities.main;

import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.example.smartstore1.R;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.utils.helpers.SharedPrefManager;

public class CompanyProfileActivity extends AppCompatActivity {
    private EditText companyNameEditText;
    private EditText addressEditText;
    private EditText phoneEditText;
    private EditText emailEditText;
    private Button saveButton;
    private FirebaseManager firebaseManager;
    private SharedPrefManager prefManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_company_profile);

        firebaseManager = FirebaseManager.getInstance();
        prefManager = SharedPrefManager.getInstance(this);
        
        initializeViews();
        loadCompanyProfile();
        setupListeners();
    }

    private void initializeViews() {        companyNameEditText = findViewById(R.id.companyNameInput);
        addressEditText = findViewById(R.id.addressInput);
        emailEditText = findViewById(R.id.emailEditText);
        saveButton = findViewById(R.id.saveButton);
    }

    private void loadCompanyProfile() {
        // Load company profile from Firebase
    }

    private void setupListeners() {
        saveButton.setOnClickListener(v -> saveCompanyProfile());
    }

    private void saveCompanyProfile() {
        String companyName = companyNameEditText.getText().toString().trim();
        String address = addressEditText.getText().toString().trim();
        String phone = phoneEditText.getText().toString().trim();
        String email = emailEditText.getText().toString().trim();

        // Save to Firebase and SharedPreferences
        // Show success message
        Toast.makeText(this, "Company profile updated successfully", Toast.LENGTH_SHORT).show();
    }
}
