package com.example.smartstore1.repositories;

import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.database.firebase.ProductRepository;
import com.example.smartstore1.models.Product;
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
import java.util.List;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.verify;
import static org.mockito.Mockito.when;
import static org.junit.Assert.*;

@RunWith(RobolectricTestRunner.class)
public class ProductRepositoryTest {
    @Mock
    private FirebaseDatabase firebaseDatabase;
    
    @Mock
    private DatabaseReference databaseReference;
    
    @Mock
    private DataSnapshot dataSnapshot;
    
    @Mock
    private Task<Void> voidTask;
    
    private ProductRepository productRepository;
    private Product testProduct;

    @Before
    public void setup() {
        MockitoAnnotations.openMocks(this);
        
        // Setup Firebase mocks
        when(firebaseDatabase.getReference("products")).thenReturn(databaseReference);
        when(databaseReference.child(any())).thenReturn(databaseReference);
        when(databaseReference.setValue(any())).thenReturn(voidTask);
        
        // Initialize repository
        productRepository = new ProductRepository(firebaseDatabase);
        
        // Create test product
        testProduct = new Product();
        testProduct.setId("test123");
        testProduct.setName("Test Product");
        testProduct.setPrice(99.99);
        testProduct.setStock(10);
        testProduct.setCategoryId("cat123");
    }

    @Test
    public void addProduct_ShouldCallSetValue() {
        // When
        Task<Void> result = productRepository.addProduct(testProduct);
        
        // Then
        verify(databaseReference).setValue(testProduct);
        assertNotNull(result);
    }

    @Test
    public void deleteProduct_ShouldCallRemoveValue() {
        // When
        Task<Void> result = productRepository.deleteProduct(testProduct.getId());
        
        // Then
        verify(databaseReference).removeValue();
        assertNotNull(result);
    }

    @Test
    public void updateProduct_ShouldCallUpdateChildren() {
        // When
        Task<Void> result = productRepository.updateProduct(testProduct);
        
        // Then
        verify(databaseReference).setValue(testProduct);
        assertNotNull(result);
    }

    @Test
    public void getProduct_ShouldReturnCorrectProduct() {
        // Given
        when(dataSnapshot.getValue(Product.class)).thenReturn(testProduct);
        
        // When
        Task<Product> result = productRepository.getProduct(testProduct.getId());
        
        // Then
        assertNotNull(result);
        verify(databaseReference).get();
    }

    @Test
    public void getAllProducts_ShouldReturnListOfProducts() {
        // Given
        List<Product> productList = new ArrayList<>();
        productList.add(testProduct);
        when(dataSnapshot.getChildren()).thenReturn(new ArrayList<DataSnapshot>());
        
        // When
        Task<List<Product>> result = productRepository.getAllProducts();
        
        // Then
        assertNotNull(result);
        verify(databaseReference).get();
    }
}
