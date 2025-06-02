package com.example.smartstore1.database.firebase;

import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.example.smartstore1.models.Receipt;
import com.example.smartstore1.utils.AppConstants;

import java.util.Date;

public class ReceiptRepository {
    private final DatabaseReference receiptsRef;

    public ReceiptRepository(FirebaseDatabase database) {
        receiptsRef = database.getReference(AppConstants.REF_RECEIPTS);
    }

    public Task<Void> addReceipt(Receipt receipt) {
        return receiptsRef.child(receipt.getId()).setValue(receipt);
    }

    public Task<Void> updateReceipt(Receipt receipt) {
        return receiptsRef.child(receipt.getId()).setValue(receipt);
    }

    public Task<Void> deleteReceipt(String receiptId) {
        return receiptsRef.child(receiptId).removeValue();
    }

    public Task<DataSnapshot> getReceiptBySaleId(String saleId) {
        return receiptsRef.orderByChild("saleId").equalTo(saleId).get();
    }

    public Task<DataSnapshot> getReceipt(String receiptId) {
        return receiptsRef.child(receiptId).get();
    }

    public Task<DataSnapshot> getAllReceipts() {
        return receiptsRef.get();
    }

    public Task<DataSnapshot> getReceiptsByDateRange(Date startDate, Date endDate) {
        return receiptsRef.orderByChild("date")
                         .startAt(startDate.getTime())
                         .endAt(endDate.getTime())
                         .get();
    }
}
