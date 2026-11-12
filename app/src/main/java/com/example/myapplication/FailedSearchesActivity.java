package com.example.myapplication;

import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import java.util.ArrayList;

public class FailedSearchesActivity extends AppCompatActivity {

    private DatabaseHelper dbHelper;
    private TextView txtFailedCount;
    private ListView listFailedSearches;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_failed_searches);

        dbHelper = new DatabaseHelper(this);

        txtFailedCount = findViewById(R.id.txtFailedCount);
        listFailedSearches = findViewById(R.id.listFailedSearches);

        loadFailedSearches();
    }

    private void loadFailedSearches() {

        int total = dbHelper.getFailedSearchesCount();

        txtFailedCount.setText(
                total + " recherche(s) infructueuse(s) enregistrée(s)"
        );

        ArrayList<String> searches =
                dbHelper.getFailedSearchesSummary();

        if (searches.isEmpty()) {
            searches.add("Aucune recherche infructueuse enregistrée.");
        }

        ArrayAdapter<String> adapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_list_item_1,
                searches
        );

        listFailedSearches.setAdapter(adapter);
    }
}