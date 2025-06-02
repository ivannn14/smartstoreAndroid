package com.example.smartstore1.activities.main;

import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.widget.Toolbar;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import com.google.android.material.navigation.NavigationView;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.example.smartstore1.R;
import com.example.smartstore1.activities.auth.LoginActivity;
import com.example.smartstore1.fragments.DashboardFragment;
import com.example.smartstore1.fragments.ProductsFragment;
import com.example.smartstore1.fragments.CategoriesFragment;
import com.example.smartstore1.fragments.SalesFragment;
import com.example.smartstore1.fragments.CustomersFragment;
import com.example.smartstore1.fragments.ReportsFragment;
import com.example.smartstore1.fragments.SettingsFragment;
import com.example.smartstore1.utils.helpers.SharedPrefManager;

public class MainActivity extends AppCompatActivity {
    private FirebaseAuth firebaseAuth;
    private FirebaseAuth.AuthStateListener authStateListener;
    private Toolbar toolbar;
    private DrawerLayout drawerLayout;
    private NavigationView navigationView;
    private BottomNavigationView bottomNavigationView;
    private SharedPrefManager prefManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dashboard);

        // Initialize Firebase Auth and SharedPrefManager
        firebaseAuth = FirebaseAuth.getInstance();
        prefManager = SharedPrefManager.getInstance(this);

        initializeViews();
        setupToolbar();
        setupNavigationDrawer();
        setupBottomNavigation();

        // Set up auth state listener
        authStateListener = firebaseAuth -> {
            FirebaseUser user = firebaseAuth.getCurrentUser();
            if (user == null || !prefManager.isLoggedIn()) {
                // User is signed out or preferences are cleared
                startActivity(new Intent(MainActivity.this, LoginActivity.class));
                finish();
            }
        };
        firebaseAuth.addAuthStateListener(authStateListener);        // Set default fragment and navigation state
        if (savedInstanceState == null) {
            loadFragment(new DashboardFragment());
            navigationView.setCheckedItem(R.id.nav_dashboard);
            bottomNavigationView.setSelectedItemId(R.id.nav_home);
        }
    }

    @Override
    protected void onStop() {
        super.onStop();
        if (authStateListener != null) {
            firebaseAuth.removeAuthStateListener(authStateListener);
        }
    }

    private void initializeViews() {
        toolbar = findViewById(R.id.toolbar);
        drawerLayout = findViewById(R.id.drawer_layout);
        navigationView = findViewById(R.id.navigationView);
        bottomNavigationView = findViewById(R.id.bottom_navigation);
    }

    private void setupToolbar() {
        setSupportActionBar(toolbar);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawerLayout, toolbar,
                R.string.navigation_drawer_open,
                R.string.navigation_drawer_close
        );
        drawerLayout.addDrawerListener(toggle);
        toggle.syncState();
    }

    private void setupNavigationDrawer() {
        navigationView.setNavigationItemSelectedListener(item -> {
            Fragment selectedFragment = null;
            int itemId = item.getItemId();

            if (itemId == R.id.nav_dashboard) {
                selectedFragment = new DashboardFragment();
            } else if (itemId == R.id.nav_products) {
                selectedFragment = new ProductsFragment();
            } else if (itemId == R.id.nav_sales) {
                selectedFragment = new SalesFragment();
            } else if (itemId == R.id.nav_reports) {
                selectedFragment = new ReportsFragment();
            } else if (itemId == R.id.nav_settings) {
                startActivity(new Intent(this, SettingsActivity.class));
                drawerLayout.closeDrawer(GravityCompat.START);
                return true;
            } else if (itemId == R.id.nav_logout) {
                logout();
                return true;
            }

            if (selectedFragment != null) {
                loadFragment(selectedFragment);
            }

            drawerLayout.closeDrawer(GravityCompat.START);
            return true;
        });
    }

    private void setupBottomNavigation() {
        bottomNavigationView.setOnItemSelectedListener(item -> {
            Fragment selectedFragment = null;
            int itemId = item.getItemId();

            if (itemId == R.id.nav_home) {
                selectedFragment = new DashboardFragment();
                navigationView.setCheckedItem(R.id.nav_dashboard);
            } else if (itemId == R.id.nav_products) {
                selectedFragment = new ProductsFragment();
                navigationView.setCheckedItem(R.id.nav_products);
            } else if (itemId == R.id.nav_sales) {
                selectedFragment = new SalesFragment();
                navigationView.setCheckedItem(R.id.nav_sales);
            } else if (itemId == R.id.nav_reports) {
                selectedFragment = new ReportsFragment();
                navigationView.setCheckedItem(R.id.nav_reports);
            }

            if (selectedFragment != null) {
                loadFragment(selectedFragment);
                return true;
            }
            return false;
        });
    }    private void loadFragment(Fragment fragment) {
        // Use commitNow to ensure the fragment is loaded immediately
        getSupportFragmentManager()
                .beginTransaction()
                .setReorderingAllowed(true)
                .replace(R.id.fragment_container, fragment)
                .commitNow();
    }

    private void logout() {
        // Sign out from Firebase
        firebaseAuth.signOut();
        
        // Clear preferences
        prefManager.clear();
        
        // Navigate to login screen
        startActivity(new Intent(this, LoginActivity.class));
        finish();
    }

    @Override
    public void onBackPressed() {
        if (drawerLayout.isDrawerOpen(GravityCompat.START)) {
            drawerLayout.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }
}
