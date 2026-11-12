package com.example.myapplication;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class OwnerProfileActivity extends AppCompatActivity {

    private TextView txtOwnerName;
    private TextView txtOwnerEmail;
    private TextView txtOwnerPhone;
    private TextView txtOwnerPharmacy;
    private Button btnManageOwner;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_owner_profile);

        txtOwnerName = findViewById(R.id.txtOwnerName);
        txtOwnerEmail = findViewById(R.id.txtOwnerEmail);
        txtOwnerPhone = findViewById(R.id.txtOwnerPhone);
        txtOwnerPharmacy = findViewById(R.id.txtOwnerPharmacy);
        btnManageOwner = findViewById(R.id.btnManageOwner);

        txtOwnerName.setText(
                getIntent().getStringExtra("ownerName")
        );

        txtOwnerEmail.setText(
                getIntent().getStringExtra("ownerEmail")
        );

        txtOwnerPhone.setText(
                getIntent().getStringExtra("ownerPhone")
        );

        txtOwnerPharmacy.setText(
                getIntent().getStringExtra("pharmacyName")
        );

        btnManageOwner.setOnClickListener(v ->
                Toast.makeText(
                        this,
                        "La gestion du compte sera disponible ultérieurement.",
                        Toast.LENGTH_SHORT
                ).show()
        );
    }
}