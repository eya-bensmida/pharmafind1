package com.example.myapplication;

import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.Button;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;

public class AdminDashboardActivity extends AppCompatActivity {

    private DatabaseHelper dbHelper;
    private PharmacyAdapter adapter;
    private ArrayList<Pharmacy> pharmacyList;

    private RecyclerView recyclerPharmacies;
    private EditText edtSearch;
    private Button btnAddPharmacy;
    private Button btnFailedSearches;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_admin_dashboard);

        dbHelper = new DatabaseHelper(this);

        recyclerPharmacies = findViewById(R.id.recyclerPharmacies);
        edtSearch = findViewById(R.id.edtSearch);
        btnAddPharmacy = findViewById(R.id.btnAddPharmacy);
        btnFailedSearches = findViewById(R.id.btnFailedSearches);

        recyclerPharmacies.setLayoutManager(
                new LinearLayoutManager(this)
        );

        pharmacyList = new ArrayList<>();
        adapter = new PharmacyAdapter(this, pharmacyList);
        recyclerPharmacies.setAdapter(adapter);

        btnAddPharmacy.setOnClickListener(v -> {
            Intent intent = new Intent(
                    AdminDashboardActivity.this,
                    AddPharmacyActivity.class
            );
            startActivity(intent);
        });

        btnFailedSearches.setOnClickListener(v -> {
            Intent intent = new Intent(
                    AdminDashboardActivity.this,
                    FailedSearchesActivity.class
            );
            startActivity(intent);
        });

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

        loadPharmacies();
    }

    @Override
    protected void onResume() {
        super.onResume();
        loadPharmacies();
    }

    private void loadPharmacies() {
        pharmacyList.clear();
        pharmacyList.addAll(dbHelper.getAllPharmacies());

        if (adapter != null) {
            adapter.updateList(pharmacyList);
        }
    }
}