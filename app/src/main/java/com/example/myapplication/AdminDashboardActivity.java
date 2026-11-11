package com.example.myapplication;


import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.List;

public class AdminDashboardActivity extends AppCompatActivity {

    RecyclerView recyclerPharmacies;
    EditText edtSearch;
    Button btnAddPharmacy;

    PharmacyAdapter adapter;
    List<Pharmacy> pharmacyList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_admin_dashboard);

        recyclerPharmacies = findViewById(R.id.recyclerPharmacies);
        edtSearch = findViewById(R.id.edtSearch);
        btnAddPharmacy = findViewById(R.id.btnAddPharmacy);

        // RecyclerView configuration
        recyclerPharmacies.setLayoutManager(
                new LinearLayoutManager(this)
        );

        // Sample pharmacies
        pharmacyList = new ArrayList<>();

        pharmacyList.add(new Pharmacy(
                "Pharmacie Centrale",
                "Tunis, Centre Ville",
                "Amira Ben Ali",
                "amira@gmail.com",
                "22123456",
                android.R.drawable.ic_menu_myplaces
        ));

        pharmacyList.add(new Pharmacy(
                "Pharmacie El Wafa",
                "Ariana, Ennasr",
                "Mohamed Trabelsi",
                "mohamed@gmail.com",
                "22111222",
                android.R.drawable.ic_menu_myplaces
        ));

        pharmacyList.add(new Pharmacy(
                "Pharmacie de la Santé",
                "Manouba, Centre",
                "Sarra Mansouri",
                "sarra@gmail.com",
                "55123456",
                android.R.drawable.ic_menu_myplaces
        ));

        // Adapter
        adapter = new PharmacyAdapter(this, pharmacyList);
        recyclerPharmacies.setAdapter(adapter);

        // Search pharmacy
        edtSearch.addTextChangedListener(new TextWatcher() {

            @Override
            public void beforeTextChanged(CharSequence s, int start,
                                          int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start,
                                      int before, int count) {

                adapter.filter(s.toString());
            }

            @Override
            public void afterTextChanged(Editable s) {
            }
        });

        // Add pharmacy button
        btnAddPharmacy.setOnClickListener(v -> {

            Toast.makeText(this,
                    "Ouverture du formulaire d'ajout",
                    Toast.LENGTH_SHORT).show();

            // Nous ajouterons ici la navigation vers AddPharmacyActivity.
        });
    }
}
