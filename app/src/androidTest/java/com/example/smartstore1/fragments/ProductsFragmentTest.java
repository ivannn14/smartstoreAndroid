package com.example.smartstore1.fragments;

import androidx.fragment.app.testing.FragmentScenario;
import androidx.test.espresso.Espresso;
import androidx.test.espresso.assertion.ViewAssertions;
import androidx.test.espresso.matcher.ViewMatchers;
import androidx.test.ext.junit.runners.AndroidJUnit4;
import com.example.smartstore1.R;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;

@RunWith(AndroidJUnit4.class)
public class ProductsFragmentTest {
    private FragmentScenario<ProductsFragment> fragmentScenario;

    @Before
    public void setup() {
        fragmentScenario = FragmentScenario.launchInContainer(ProductsFragment.class);
    }

    @Test
    public void testProductsFragmentVisible() {
        // Verify that the RecyclerView is displayed
        Espresso.onView(ViewMatchers.withId(R.id.products_recycler))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
    }

    @Test
    public void testAddProductFabVisible() {
        // Verify that the FAB is displayed
        Espresso.onView(ViewMatchers.withId(R.id.add_product_fab))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
    }

    @Test
    public void testLoadingIndicatorInitiallyVisible() {
        // Verify that the progress indicator is initially visible
        Espresso.onView(ViewMatchers.withId(R.id.progress_indicator))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
    }

    @Test
    public void testSwipeRefreshLayoutVisible() {
        // Verify that the SwipeRefreshLayout is displayed
        Espresso.onView(ViewMatchers.withId(R.id.swipe_refresh))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
    }

    // TODO: Add more tests for:
    // - Adding a product
    // - Editing a product
    // - Deleting a product
    // - Error states
    // - Empty states
    // - Data loading
}
