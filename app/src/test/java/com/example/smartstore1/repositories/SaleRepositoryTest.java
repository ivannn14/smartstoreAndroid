package com.example.smartstore1.repositories;

import com.example.smartstore1.database.firebase.SaleRepository;
import com.example.smartstore1.models.Sale;
import com.example.smartstore1.models.SaleItem;
import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.mockito.Mock;
import org.mockito.MockitoAnnotations;
import org.robolectric.RobolectricTestRunner;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.verify;
import static org.mockito.Mockito.when;
import static org.junit.Assert.*;

@RunWith(RobolectricTestRunner.class)
public class SaleRepositoryTest {
    @Mock
    private FirebaseDatabase firebaseDatabase;
    
    @Mock
    private DatabaseReference databaseReference;
    
    @Mock
    private DataSnapshot dataSnapshot;
    
    @Mock
    private Task<Void> voidTask;
    
    private SaleRepository saleRepository;
    private Sale testSale;

    @Before
    public void setup() {
        MockitoAnnotations.openMocks(this);
        
        // Setup Firebase mocks
        when(firebaseDatabase.getReference("sales")).thenReturn(databaseReference);
        when(databaseReference.child(any())).thenReturn(databaseReference);
        when(databaseReference.setValue(any())).thenReturn(voidTask);
        
        // Initialize repository
        saleRepository = new SaleRepository(firebaseDatabase);
        
        // Create test sale
        testSale = new Sale();
        testSale.setId("sale123");
        testSale.setDate(new Date());
        testSale.setCustomerId("cust123");
        testSale.setTotal(299.99);
        
        List<SaleItem> items = new ArrayList<>();
        SaleItem item = new SaleItem();
        item.setProductId("prod123");
        item.setQuantity(2);
        item.setPrice(149.99);
        items.add(item);
        testSale.setItems(items);
    }

    @Test
    public void addSale_ShouldCallSetValue() {
        // When
        Task<Void> result = saleRepository.addSale(testSale);
        
        // Then
        verify(databaseReference).setValue(testSale);
        assertNotNull(result);
    }

    @Test
    public void deleteSale_ShouldCallRemoveValue() {
        // When
        Task<Void> result = saleRepository.deleteSale(testSale.getId());
        
        // Then
        verify(databaseReference).removeValue();
        assertNotNull(result);
    }

    @Test
    public void getSale_ShouldReturnCorrectSale() {
        // Given
        when(dataSnapshot.getValue(Sale.class)).thenReturn(testSale);
        
        // When
        Task<Sale> result = saleRepository.getSale(testSale.getId());
        
        // Then
        assertNotNull(result);
        verify(databaseReference).get();
    }

    @Test
    public void getAllSales_ShouldReturnListOfSales() {
        // Given
        List<Sale> saleList = new ArrayList<>();
        saleList.add(testSale);
        when(dataSnapshot.getChildren()).thenReturn(new ArrayList<DataSnapshot>());
        
        // When
        Task<List<Sale>> result = saleRepository.getAllSales();
        
        // Then
        assertNotNull(result);
        verify(databaseReference).get();
    }

    @Test
    public void getSalesByDateRange_ShouldFilterCorrectly() {
        // Given
        Date startDate = new Date(System.currentTimeMillis() - 86400000); // Yesterday
        Date endDate = new Date(); // Today
        
        // When
        Task<List<Sale>> result = saleRepository.getSalesByDateRange(startDate, endDate);
        
        // Then
        assertNotNull(result);
        verify(databaseReference).get();
    }

    @Test
    public void getSalesByCustomer_ShouldFilterCorrectly() {
        // Given
        String customerId = "cust123";
        
        // When
        Task<List<Sale>> result = saleRepository.getSalesByCustomer(customerId);
        
        // Then
        assertNotNull(result);
        verify(databaseReference).get();
    }

    @Test
    public void calculateTotalSales_ShouldSumCorrectly() {
        // Given
        List<Sale> sales = new ArrayList<>();
        sales.add(testSale);
        Sale anotherSale = new Sale();
        anotherSale.setTotal(200.00);
        sales.add(anotherSale);
        
        // When
        double total = saleRepository.calculateTotalSales(sales);
        
        // Then
        assertEquals(499.99, total, 0.01);
    }
}
