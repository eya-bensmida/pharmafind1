package com.example.myapplication;


import android.os.Bundle;
import android.widget.TextView;
import android.widget.Button;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class OwnerProfileActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_owner_profile);

        TextView name = findViewById(R.id.txtOwnerName);
        TextView email = findViewById(R.id.txtOwnerEmail);
        TextView phone = findViewById(R.id.txtOwnerPhone);
        TextView pharmacy = findViewById(R.id.txtOwnerPharmacy);

        Button manage = findViewById(R.id.btnManageOwner);

        name.setText(getIntent().getStringExtra("ownerName"));
        email.setText(getIntent().getStringExtra("ownerEmail"));
        phone.setText(getIntent().getStringExtra("ownerPhone"));
        pharmacy.setText(getIntent().getStringExtra("pharmacyName"));

        manage.setOnClickListener(v -> {
            Toast.makeText(this,
                    "Gestion du compte propriétaire",
                    Toast.LENGTH_SHORT).show();
        });
    }
}
