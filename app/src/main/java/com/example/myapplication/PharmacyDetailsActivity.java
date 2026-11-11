package com.example.myapplication;


import android.os.Bundle;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

public class PharmacyDetailsActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pharmacy_details);

        TextView name = findViewById(R.id.txtDetailName);
        TextView address = findViewById(R.id.txtDetailAddress);
        TextView owner = findViewById(R.id.txtDetailOwner);

        name.setText(getIntent().getStringExtra("pharmacyName"));
        address.setText(getIntent().getStringExtra("address"));
        owner.setText(getIntent().getStringExtra("ownerName"));
    }
}
