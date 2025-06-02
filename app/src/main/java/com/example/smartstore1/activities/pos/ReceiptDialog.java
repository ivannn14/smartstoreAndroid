package com.example.smartstore1.activities.pos;

import android.app.Dialog;
import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothManager;
import android.bluetooth.BluetoothSocket;
import android.content.Context;
import android.content.Intent;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.pdf.PdfDocument;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;

import com.example.smartstore1.models.Product;
import com.example.smartstore1.models.CartItem;
import com.example.smartstore1.models.SaleItem;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.google.android.material.button.MaterialButton;
import com.example.smartstore1.R;
import com.example.smartstore1.models.Receipt;
import com.example.smartstore1.models.CartItem;
import com.example.smartstore1.models.SaleItem;
import com.example.smartstore1.models.Product;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;
import java.util.Set;
import java.util.UUID;

public class ReceiptDialog extends Dialog {
    private final Receipt receipt;
    private final NumberFormat currencyFormatter;
    private final SimpleDateFormat dateFormatter;
    
    private static final int RECEIPT_WIDTH_MM = 80; // Standard thermal receipt width
    private static final float MM_TO_PIXELS = 72f / 25.4f; // 72 DPI / 25.4 mm per inch
    private final BluetoothAdapter bluetoothAdapter;
    private BluetoothDevice printerDevice;

    public ReceiptDialog(@NonNull Context context, Receipt receipt) {
        super(context);
        this.receipt = receipt;
        this.currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "PH"));
        this.dateFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
        
        // Initialize Bluetooth
        BluetoothManager bluetoothManager = (BluetoothManager) context.getSystemService(Context.BLUETOOTH_SERVICE);
        this.bluetoothAdapter = bluetoothManager != null ? bluetoothManager.getAdapter() : null;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.dialog_receipt);

        LinearLayout receiptContent = findViewById(R.id.receiptContent);
        MaterialButton printButton = findViewById(R.id.printButton);
        MaterialButton thermalPrintButton = findViewById(R.id.thermalPrintButton);
        MaterialButton closeButton = findViewById(R.id.closeButton);

        // Convert SaleItems to CartItems for display
        List<CartItem> cartItems = convertSaleItemsToCartItems(receipt.getItems());
        displayReceipt(receiptContent, cartItems);
        
        printButton.setOnClickListener(v -> generatePdfReceipt());
        thermalPrintButton.setOnClickListener(v -> printToThermalPrinter());
        closeButton.setOnClickListener(v -> dismiss());

        // Check if thermal printing is available
        thermalPrintButton.setVisibility(bluetoothAdapter != null ? View.VISIBLE : View.GONE);
    }

    private void displayReceipt(LinearLayout container, List<CartItem> items) {
        // Header
        addTextView(container, "SmartStore", 18, true);
        addTextView(container, "Receipt #: " + receipt.getId());
        addTextView(container, "Date: " + dateFormatter.format(receipt.getDate()));
        addTextView(container, "Cashier: " + receipt.getCashierName());
        addTextView(container, "Customer: " + receipt.getCustomerName());
        addDivider(container);

        // Items
        addTextView(container, "Items:", 16, true);
        for (CartItem item : items) {
            String itemLine = String.format("%s x%d", 
                item.getProduct().getName(), 
                item.getQuantity());
            String priceLine = String.format("%s @ %s", 
                currencyFormatter.format(item.getProduct().getPrice()),
                currencyFormatter.format(item.getProduct().getPrice() * item.getQuantity()));
            addTextView(container, itemLine);
            addTextView(container, priceLine);
        }
        addDivider(container);

        // Totals
        addTextView(container, "Subtotal: " + currencyFormatter.format(receipt.getSubtotal()));
        addTextView(container, "Tax: " + currencyFormatter.format(receipt.getTax()));
        addTextView(container, "Total: " + currencyFormatter.format(receipt.getTotal()), 16, true);
        addTextView(container, "Amount Paid: " + currencyFormatter.format(receipt.getAmountPaid()));
        addTextView(container, "Change: " + currencyFormatter.format(receipt.getChange()));
        addDivider(container);

        // Footer
        addTextView(container, "Payment Method: " + receipt.getPaymentMethod());
        addTextView(container, "Thank you for shopping with us!");
    }

    private void addTextView(LinearLayout container, String text) {
        addTextView(container, text, 14, false);
    }

    private void addTextView(LinearLayout container, String text, int textSizeSp, boolean bold) {
        TextView textView = new TextView(getContext());
        textView.setText(text);
        textView.setTextSize(textSizeSp);
        if (bold) {
            textView.setTypeface(textView.getTypeface(), android.graphics.Typeface.BOLD);
        }
        container.addView(textView);
    }

    private void addDivider(LinearLayout container) {
        View divider = new View(getContext());
        divider.setBackgroundColor(0xFF000000);
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
            LinearLayout.LayoutParams.MATCH_PARENT, 1);
        params.setMargins(0, 8, 0, 8);
        divider.setLayoutParams(params);
        container.addView(divider);
    }    private void generatePdfReceipt() {
        // Create receipt-sized PDF (80mm width typical for thermal receipts)
        int pageWidth = 595; // A4 width for better compatibility
        int pageHeight = 842; // A4 height
        PdfDocument document = new PdfDocument();
        PdfDocument.PageInfo pageInfo = new PdfDocument.PageInfo.Builder(pageWidth, pageHeight, 1).create();
        PdfDocument.Page page = document.startPage(pageInfo);

        Canvas canvas = page.getCanvas();
        Paint paint = new Paint();
        paint.setAntiAlias(true);
        paint.setTextSize(12);
        
        // Draw receipt content on PDF
        float y = 50;
        y = drawText(canvas, paint, "SmartStore", 250, y, 16, true);
        y = drawText(canvas, paint, "Receipt #: " + receipt.getId(), 50, y);
        y = drawText(canvas, paint, "Date: " + dateFormatter.format(receipt.getDate()), 50, y);
        y = drawText(canvas, paint, "Cashier: " + receipt.getCashierName(), 50, y);
        y = drawText(canvas, paint, "Customer: " + receipt.getCustomerName(), 50, y);
        y = drawLine(canvas, paint, y);

        // Items
        y = drawText(canvas, paint, "Items:", 50, y, 14, true);
        List<CartItem> cartItems = convertSaleItemsToCartItems(receipt.getItems());
        for (CartItem item : cartItems) {
            String itemLine = String.format("%s x%d", 
                item.getProduct().getName(), 
                item.getQuantity());
            String priceLine = String.format("%s @ %s", 
                currencyFormatter.format(item.getProduct().getPrice()),
                currencyFormatter.format(item.getProduct().getPrice() * item.getQuantity()));
            y = drawText(canvas, paint, itemLine, 50, y);
            y = drawText(canvas, paint, priceLine, 350, y - 15);
        }
        y = drawLine(canvas, paint, y);

        // Totals
        y = drawText(canvas, paint, "Subtotal: " + currencyFormatter.format(receipt.getSubtotal()), 50, y);
        y = drawText(canvas, paint, "Tax: " + currencyFormatter.format(receipt.getTax()), 50, y);
        y = drawText(canvas, paint, "Total: " + currencyFormatter.format(receipt.getTotal()), 50, y, 14, true);
        y = drawText(canvas, paint, "Amount Paid: " + currencyFormatter.format(receipt.getAmountPaid()), 50, y);
        y = drawText(canvas, paint, "Change: " + currencyFormatter.format(receipt.getChange()), 50, y);
        y = drawLine(canvas, paint, y);

        // Footer
        y = drawText(canvas, paint, "Payment Method: " + receipt.getPaymentMethod(), 50, y);
        drawText(canvas, paint, "Thank you for shopping with us!", 50, y);

        document.finishPage(page);

        // Save PDF
        File outputDir = new File(getContext().getFilesDir(), "receipts");
        if (!outputDir.exists()) {
            outputDir.mkdirs();
        }
        
        File outputFile = new File(outputDir, "receipt_" + receipt.getId() + ".pdf");
        try {
            document.writeTo(new FileOutputStream(outputFile));
            Toast.makeText(getContext(), 
                "Receipt saved: " + outputFile.getAbsolutePath(), 
                Toast.LENGTH_LONG).show();
        } catch (IOException e) {
            e.printStackTrace();
            Toast.makeText(getContext(), 
                "Failed to save receipt", 
                Toast.LENGTH_SHORT).show();
        }
        document.close();
    }

    private void printToThermalPrinter() {
        if (bluetoothAdapter == null) {
            Toast.makeText(getContext(), "Bluetooth is not available", Toast.LENGTH_SHORT).show();
            return;
        }

        if (!bluetoothAdapter.isEnabled()) {
            Intent enableBtIntent = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
            getContext().startActivity(enableBtIntent);
            return;
        }

        // Show printer selection dialog
        showPrinterSelectionDialog();
    }

    private void showPrinterSelectionDialog() {
        Set<BluetoothDevice> pairedDevices = bluetoothAdapter.getBondedDevices();
        if (pairedDevices.size() > 0) {
            final BluetoothDevice[] devices = pairedDevices.toArray(new BluetoothDevice[0]);
            String[] deviceNames = new String[devices.length];
            for (int i = 0; i < devices.length; i++) {
                deviceNames[i] = devices[i].getName();
            }

            new AlertDialog.Builder(getContext())
                .setTitle("Select Printer")
                .setItems(deviceNames, (dialog, which) -> {
                    printerDevice = devices[which];
                    sendDataToPrinter();
                })
                .setNegativeButton("Cancel", null)
                .show();
        } else {
            Toast.makeText(getContext(), "No paired printers found", Toast.LENGTH_SHORT).show();
        }
    }

    private void sendDataToPrinter() {
        if (printerDevice == null) return;

        try {
            BluetoothSocket socket = printerDevice.createRfcommSocketToServiceRecord(
                UUID.fromString("00001101-0000-1000-8000-00805F9B34FB")); // Standard SerialPort service UUID
            
            socket.connect();
            OutputStream outputStream = socket.getOutputStream();

            // Send receipt data in ESC/POS format
            byte[] data = generateEscPosData();
            outputStream.write(data);
            outputStream.flush();
            outputStream.close();
            socket.close();

            Toast.makeText(getContext(), "Receipt printed successfully", Toast.LENGTH_SHORT).show();
        } catch (IOException e) {
            e.printStackTrace();
            Toast.makeText(getContext(), "Failed to print receipt", Toast.LENGTH_SHORT).show();
        }
    }

    private byte[] generateEscPosData() {
        ByteArrayOutputStream data = new ByteArrayOutputStream();
        try {
            // Initialize printer
            data.write(new byte[] { 0x1B, 0x40 }); // ESC @ - Initialize printer

            // Store name - double size
            data.write(new byte[] { 0x1B, 0x21, 0x30 }); // ESC ! 0 - Select print mode (double width/height)
            data.write("SmartStore\n".getBytes());
            data.write(new byte[] { 0x1B, 0x21, 0x00 }); // Reset print mode

            // Receipt details
            String header = String.format(
                "Receipt #: %s\nDate: %s\nCashier: %s\nCustomer: %s\n\n",
                receipt.getId(),
                dateFormatter.format(receipt.getDate()),
                receipt.getCashierName(),
                receipt.getCustomerName()
            );
            data.write(header.getBytes());

            // Items
            data.write("Items:\n".getBytes());
            List<CartItem> cartItems = convertSaleItemsToCartItems(receipt.getItems());
            for (CartItem item : cartItems) {
                String itemLine = String.format(
                    "%s x%d\n%s @ %s\n",
                    item.getProduct().getName(),
                    item.getQuantity(),
                    currencyFormatter.format(item.getProduct().getPrice()),
                    currencyFormatter.format(item.getProduct().getPrice() * item.getQuantity())
                );
                data.write(itemLine.getBytes());
            }

            // Separator
            data.write("--------------------------------\n".getBytes());

            // Totals
            String totals = String.format(
                "Subtotal: %s\nTax: %s\nTotal: %s\n\nAmount Paid: %s\nChange: %s\n\nPayment Method: %s\n",
                currencyFormatter.format(receipt.getSubtotal()),
                currencyFormatter.format(receipt.getTax()),
                currencyFormatter.format(receipt.getTotal()),
                currencyFormatter.format(receipt.getAmountPaid()),
                currencyFormatter.format(receipt.getChange()),
                receipt.getPaymentMethod()
            );
            data.write(totals.getBytes());

            // Footer
            data.write("\nThank you for shopping with us!\n\n\n\n".getBytes());

            // Cut paper
            data.write(new byte[] { 0x1D, 0x56, 0x41, 0x10 }); // GS V A - Full cut with feed
        } catch (IOException e) {
            e.printStackTrace();
        }
        return data.toByteArray();
    }

    private float drawText(Canvas canvas, Paint paint, String text, float x, float y) {
        return drawText(canvas, paint, text, x, y, 12, false);
    }

    private float drawText(Canvas canvas, Paint paint, String text, float x, float y, 
                          float textSize, boolean bold) {
        paint.setTextSize(textSize);
        paint.setFakeBoldText(bold);
        canvas.drawText(text, x, y, paint);
        return y + textSize + 10;
    }

    private float drawLine(Canvas canvas, Paint paint, float y) {
        paint.setStrokeWidth(1);
        canvas.drawLine(50, y, 545, y, paint);
        return y + 20;
    }    private List<CartItem> convertSaleItemsToCartItems(List<SaleItem> saleItems) {
        List<CartItem> cartItems = new ArrayList<>();
        for (SaleItem saleItem : saleItems) {
            // Lookup product from the repository
            FirebaseManager.getInstance().getProductRepository()
                .getProduct(saleItem.getProductId())
                .addOnSuccessListener(snapshot -> {
                    Product product = snapshot.getValue(Product.class);
                    if (product != null) {
                        cartItems.add(new CartItem(product, saleItem.getQuantity()));
                    }
                })
                .addOnFailureListener(e -> 
                    Toast.makeText(getContext(), 
                        "Failed to load product: " + saleItem.getProductId(), 
                        Toast.LENGTH_SHORT).show());
        }
        return cartItems;
    }
}
