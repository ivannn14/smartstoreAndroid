package com.example.smartstore1.models;

import static org.junit.Assert.*;
import org.junit.Before;
import org.junit.Test;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class SaleTest {
    private Sale sale;
    private static final String TEST_ID = "sale123";
    private static final String TEST_CUSTOMER_ID = "cust123";
    private static final double TEST_TOTAL = 299.99;
    private static final double TEST_COST = 199.99;
    private static final Date TEST_DATE = new Date();

    @Before
    public void setup() {
        sale = new Sale();
        sale.setId(TEST_ID);
        sale.setCustomerId(TEST_CUSTOMER_ID);
        sale.setTotal(TEST_TOTAL);
        sale.setCost(TEST_COST);
        sale.setDate(TEST_DATE);
        
        List<SaleItem> items = new ArrayList<>();
        SaleItem item = new SaleItem();
        item.setProductId("prod123");
        item.setQuantity(2);
        item.setPrice(149.99);
        items.add(item);
        sale.setItems(items);
    }

    @Test
    public void testSaleCreation() {
        assertNotNull("Sale should not be null", sale);
    }

    @Test
    public void testGetId() {
        assertEquals("Sale ID should match", TEST_ID, sale.getId());
    }

    @Test
    public void testGetCustomerId() {
        assertEquals("Customer ID should match", TEST_CUSTOMER_ID, sale.getCustomerId());
    }

    @Test
    public void testGetTotal() {
        assertEquals("Sale total should match", TEST_TOTAL, sale.getTotal(), 0.001);
    }

    @Test
    public void testGetCost() {
        assertEquals("Sale cost should match", TEST_COST, sale.getCost(), 0.001);
    }

    @Test
    public void testGetDate() {
        assertEquals("Sale date should match", TEST_DATE, sale.getDate());
    }

    @Test
    public void testGetItems() {
        assertNotNull("Sale items should not be null", sale.getItems());
        assertEquals("Sale should have one item", 1, sale.getItems().size());
    }

    @Test
    public void testSetId() {
        String newId = "newSale123";
        sale.setId(newId);
        assertEquals("Sale ID should be updated", newId, sale.getId());
    }

    @Test
    public void testSetCustomerId() {
        String newCustomerId = "newCust123";
        sale.setCustomerId(newCustomerId);
        assertEquals("Customer ID should be updated", newCustomerId, sale.getCustomerId());
    }

    @Test
    public void testSetTotal() {
        double newTotal = 399.99;
        sale.setTotal(newTotal);
        assertEquals("Sale total should be updated", newTotal, sale.getTotal(), 0.001);
    }

    @Test
    public void testSetCost() {
        double newCost = 299.99;
        sale.setCost(newCost);
        assertEquals("Sale cost should be updated", newCost, sale.getCost(), 0.001);
    }

    @Test
    public void testSetDate() {
        Date newDate = new Date(System.currentTimeMillis() + 86400000); // Tomorrow
        sale.setDate(newDate);
        assertEquals("Sale date should be updated", newDate, sale.getDate());
    }

    @Test
    public void testSetItems() {
        List<SaleItem> newItems = new ArrayList<>();
        SaleItem item1 = new SaleItem();
        item1.setProductId("prod456");
        item1.setQuantity(1);
        item1.setPrice(99.99);
        newItems.add(item1);

        SaleItem item2 = new SaleItem();
        item2.setProductId("prod789");
        item2.setQuantity(3);
        item2.setPrice(49.99);
        newItems.add(item2);

        sale.setItems(newItems);
        assertEquals("Sale should have two items", 2, sale.getItems().size());
    }

    @Test
    public void testCalculateProfit() {
        double expectedProfit = TEST_TOTAL - TEST_COST;
        assertEquals("Profit calculation should be correct", 
                    expectedProfit, sale.calculateProfit(), 0.001);
    }

    @Test
    public void testEqualsAndHashCode() {
        Sale sameSale = new Sale();
        sameSale.setId(TEST_ID);
        sameSale.setCustomerId(TEST_CUSTOMER_ID);
        sameSale.setTotal(TEST_TOTAL);
        sameSale.setCost(TEST_COST);
        sameSale.setDate(TEST_DATE);
        sameSale.setItems(sale.getItems());

        assertTrue("Sales should be equal", sale.equals(sameSale));
        assertEquals("HashCodes should be equal", sale.hashCode(), sameSale.hashCode());
    }

    @Test
    public void testToString() {
        String toString = sale.toString();
        assertTrue("ToString should contain sale ID", toString.contains(TEST_ID));
        assertTrue("ToString should contain total", toString.contains(String.valueOf(TEST_TOTAL)));
    }
}
