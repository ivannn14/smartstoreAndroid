package com.example.smartstore1.fragments;

import androidx.fragment.app.testing.FragmentScenario;
import androidx.test.espresso.Espresso;
import androidx.test.espresso.assertion.ViewAssertions;
import androidx.test.espresso.matcher.ViewMatchers;
import androidx.test.ext.junit.runners.AndroidJUnit4;
import com.example.smartstore1.R;
import com.example.smartstore1.models.Sale;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;

import java.util.ArrayList;
import java.util.List;
import androidx.test.espresso.action.ViewActions;

@RunWith(AndroidJUnit4.class)
public class SalesFragmentTest {
    private static final String dialog_new_sale = "dialog_new_sale";

    private FragmentScenario<SalesFragment> fragmentScenario;

    @Before
    public void setup() {
        fragmentScenario = FragmentScenario.launchInContainer(SalesFragment.class);
    }

    @Test
    public void testSalesFragmentVisible() {
        // Verify that the RecyclerView is displayed
        Espresso.onView(ViewMatchers.withId(R.id.sales_recycler))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
    }

    @Test
    public void testNewSaleFabVisible() {
        // Verify that the FAB is displayed
        Espresso.onView(ViewMatchers.withId(R.id.new_sale_fab))
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

    @Test
    public void testEmptyStateVisibleWhenNoSales() {
        // Given: No sales in the database
        fragmentScenario.onFragment(fragment -> {
            fragment.updateSalesList(new ArrayList<>());
        });

        // Then: Empty state should be visible
        Espresso.onView(ViewMatchers.withId(R.id.empty_state))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
    }

    @Test
    public void testRecyclerViewVisibleWithSales() {
        // Given: Some sales in the database
        List<Sale> sales = new ArrayList<>();
        Sale testSale = new Sale();
        testSale.setId("test123");
        testSale.setTotal(99.99);
        sales.add(testSale);

        fragmentScenario.onFragment(fragment -> {
            fragment.updateSalesList(sales);
        });

        // Then: RecyclerView should be visible and empty state hidden
        Espresso.onView(ViewMatchers.withId(R.id.sales_recycler))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
        Espresso.onView(ViewMatchers.withId(R.id.empty_state))
                .check(ViewAssertions.matches(ViewMatchers.withEffectiveVisibility(
                        ViewMatchers.Visibility.GONE)));
    }

    @Test
    public void testNewSaleFabClickOpensDialog() {
        // When: Clicking the new sale FAB
        Espresso.onView(ViewMatchers.withId(R.id.new_sale_fab))
                .perform(ViewActions.click());

        // Then: New sale dialog should be displayed
        Espresso.onView(ViewMatchers.withId(R.id.dialog_new_sale))
                .check(ViewAssertions.matches(ViewMatchers.isDisplayed()));
    }

    // TODO: Add more tests for:
    // - Sale deletion
    // - Sale details viewing
    // - Error states
    // - Loading states
    // - Filter operations
    // - Sort operations
}
