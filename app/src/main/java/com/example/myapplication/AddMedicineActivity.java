package com.example.myapplication;

import android.content.ContentValues;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class AddMedicineActivity extends AppCompatActivity {

    private EditText edtName;
    private EditText edtDosage;
    private EditText edtCategory;
    private EditText edtQuantity;
    private EditText edtDescription;

    private Button btnSave;

    private DatabaseHelper databaseHelper;

    private long pharmacyId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_medicine);

        edtName = findViewById(R.id.edtMedicineName);
        edtDosage = findViewById(R.id.edtMedicineDosage);
        edtCategory = findViewById(R.id.edtMedicineCategory);
        edtQuantity = findViewById(R.id.edtMedicineQuantity);
        edtDescription = findViewById(R.id.edtMedicineDescription);

        btnSave = findViewById(R.id.btnSaveMedicine);

        databaseHelper = new DatabaseHelper(this);

        pharmacyId = getIntent().getLongExtra("pharmacy_id", -1);

        btnSave.setOnClickListener(v -> saveMedicine());
    }

    private void saveMedicine() {

        String name = edtName.getText().toString().trim();
        String dosage = edtDosage.getText().toString().trim();
        String category = edtCategory.getText().toString().trim();
        String quantityText = edtQuantity.getText().toString().trim();
        String description = edtDescription.getText().toString().trim();

        if (name.isEmpty() || dosage.isEmpty()
                || category.isEmpty() || quantityText.isEmpty()) {

            Toast.makeText(this,
                    "Veuillez remplir les champs obligatoires",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        int quantity;

        try {
            quantity = Integer.parseInt(quantityText);

            if (quantity < 0) {
                Toast.makeText(this,
                        "La quantité ne peut pas être négative",
                        Toast.LENGTH_SHORT).show();
                return;
            }

        } catch (NumberFormatException e) {
            Toast.makeText(this,
                    "Veuillez saisir une quantité valide",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        if (pharmacyId <= 0) {
            Toast.makeText(this,
                    "Identifiant de pharmacie introuvable",
                    Toast.LENGTH_LONG).show();
            return;
        }

        SQLiteDatabase db = databaseHelper.getWritableDatabase();

        db.beginTransaction();

        try {

            ContentValues medicineValues = new ContentValues();
            medicineValues.put("name", name);
            medicineValues.put("dosage", dosage);
            medicineValues.put("category", category);
            medicineValues.put("description", description);

            long medicamentId = db.insert(
                    "medicaments",
                    null,
                    medicineValues
            );

            if (medicamentId == -1) {
                throw new Exception("Erreur lors de l'ajout du médicament");
            }

            ContentValues stockValues = new ContentValues();
            stockValues.put("pharmacy_id", pharmacyId);
            stockValues.put("medicament_id", medicamentId);
            stockValues.put("quantity", quantity);

            long stockId = db.insert(
                    "pharmacy_medicaments",
                    null,
                    stockValues
            );

            if (stockId == -1) {
                throw new Exception("Erreur lors de l'enregistrement du stock");
            }

            db.setTransactionSuccessful();

            Toast.makeText(this,
                    "Médicament ajouté avec succès !",
                    Toast.LENGTH_SHORT).show();

            finish();

        } catch (Exception e) {

            Toast.makeText(this,
                    "Erreur : " + e.getMessage(),
                    Toast.LENGTH_LONG).show();

        } finally {
            db.endTransaction();
        }
    }
}