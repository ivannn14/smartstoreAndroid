package com.example.smartstore1.models;

import static org.junit.Assert.*;
import org.junit.Before;
import org.junit.Test;

public class ProductTest {
    private Product product;
    private static final String TEST_ID = "test123";
    private static final String TEST_NAME = "Test Product";
    private static final double TEST_PRICE = 99.99;
    private static final int TEST_STOCK = 10;
    private static final String TEST_CATEGORY = "cat123";

    @Before
    public void setup() {
        product = new Product();
        product.setId(TEST_ID);
        product.setName(TEST_NAME);
        product.setPrice(TEST_PRICE);
        product.setStock(TEST_STOCK);
        product.setCategoryId(TEST_CATEGORY);
    }

    @Test
    public void testProductCreation() {
        assertNotNull("Product should not be null", product);
    }

    @Test
    public void testGetId() {
        assertEquals("Product ID should match", TEST_ID, product.getId());
    }

    @Test
    public void testGetName() {
        assertEquals("Product name should match", TEST_NAME, product.getName());
    }

    @Test
    public void testGetPrice() {
        assertEquals("Product price should match", TEST_PRICE, product.getPrice(), 0.001);
    }

    @Test
    public void testGetStock() {
        assertEquals("Product stock should match", TEST_STOCK, product.getStock());
    }

    @Test
    public void testGetCategory() {
        assertEquals("Product category should match", TEST_CATEGORY, product.getCategoryId());
    }

    @Test
    public void testSetId() {
        String newId = "newId123";
        product.setId(newId);
        assertEquals("Product ID should be updated", newId, product.getId());
    }

    @Test
    public void testSetName() {
        String newName = "New Product Name";
        product.setName(newName);
        assertEquals("Product name should be updated", newName, product.getName());
    }

    @Test
    public void testSetPrice() {
        double newPrice = 149.99;
        product.setPrice(newPrice);
        assertEquals("Product price should be updated", newPrice, product.getPrice(), 0.001);
    }

    @Test
    public void testSetStock() {
        int newStock = 20;
        product.setStock(newStock);
        assertEquals("Product stock should be updated", newStock, product.getStock());
    }

    @Test
    public void testSetCategory() {
        String newCategory = "newCat123";
        product.setCategoryId(newCategory);
        assertEquals("Product category should be updated", newCategory, product.getCategoryId());
    }

    @Test
    public void testEqualsAndHashCode() {
        Product sameProduct = new Product();
        sameProduct.setId(TEST_ID);
        sameProduct.setName(TEST_NAME);
        sameProduct.setPrice(TEST_PRICE);
        sameProduct.setStock(TEST_STOCK);
        sameProduct.setCategoryId(TEST_CATEGORY);

        assertTrue("Products should be equal", product.equals(sameProduct));
        assertEquals("HashCodes should be equal", product.hashCode(), sameProduct.hashCode());
    }

    @Test
    public void testToString() {
        String toString = product.toString();
        assertTrue("ToString should contain product name", toString.contains(TEST_NAME));
        assertTrue("ToString should contain product price", toString.contains(String.valueOf(TEST_PRICE)));
    }
}
