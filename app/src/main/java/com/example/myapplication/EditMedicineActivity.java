
package com.example.myapplication;

import android.content.ContentValues;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class EditMedicineActivity extends AppCompatActivity {

    private EditText edtName, edtDosage, edtCategory;
    private EditText edtDescription, edtQuantity;

    private DatabaseHelper databaseHelper;

    private long medicineId;
    private long pharmacyId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_medicine);

        databaseHelper = new DatabaseHelper(this);

        medicineId = getIntent().getLongExtra("medicine_id", -1);
        pharmacyId = getIntent().getLongExtra("pharmacy_id", -1);

        edtName = findViewById(R.id.edtEditMedicineName);
        edtDosage = findViewById(R.id.edtEditMedicineDosage);
        edtCategory = findViewById(R.id.edtEditMedicineCategory);
        edtDescription = findViewById(R.id.edtEditMedicineDescription);
        edtQuantity = findViewById(R.id.edtEditMedicineQuantity);

        Button btnSave = findViewById(R.id.btnSaveMedicine);
        Button btnCancel = findViewById(R.id.btnCancelMedicine);

        if (medicineId <= 0 || pharmacyId <= 0) {
            Toast.makeText(this,
                    "Informations du médicament introuvables",
                    Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        loadMedicine();

        btnSave.setOnClickListener(v -> saveMedicine());

        btnCancel.setOnClickListener(v -> finish());
    }

    // Charger les informations actuelles
    private void loadMedicine() {

        SQLiteDatabase db = databaseHelper.getReadableDatabase();

        String sql =
                "SELECT m.name, m.dosage, m.category, " +
                        "m.description, pm.quantity " +
                        "FROM medicaments m " +
                        "INNER JOIN pharmacy_medicaments pm " +
                        "ON m.id = pm.medicament_id " +
                        "WHERE m.id = ? AND pm.pharmacy_id = ?";

        android.database.Cursor cursor = db.rawQuery(
                sql,
                new String[]{
                        String.valueOf(medicineId),
                        String.valueOf(pharmacyId)
                }
        );

        if (cursor.moveToFirst()) {

            edtName.setText(cursor.getString(0));
            edtDosage.setText(cursor.getString(1));
            edtCategory.setText(cursor.getString(2));
            edtDescription.setText(cursor.getString(3));
            edtQuantity.setText(String.valueOf(cursor.getInt(4)));

        } else {
            Toast.makeText(this,
                    "Médicament introuvable dans cette pharmacie",
                    Toast.LENGTH_SHORT).show();
            finish();
        }

        cursor.close();
    }

    // Enregistrer les modifications
    private void saveMedicine() {

        String name = edtName.getText().toString().trim();
        String dosage = edtDosage.getText().toString().trim();
        String category = edtCategory.getText().toString().trim();
        String description = edtDescription.getText().toString().trim();
        String quantityText = edtQuantity.getText().toString().trim();

        if (name.isEmpty() || dosage.isEmpty() || quantityText.isEmpty()) {
            Toast.makeText(this,
                    "Veuillez remplir le nom, le dosage et la quantité",
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
                    "Quantité invalide",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        SQLiteDatabase db = databaseHelper.getWritableDatabase();

        ContentValues medicineValues = new ContentValues();
        medicineValues.put("name", name);
        medicineValues.put("dosage", dosage);
        medicineValues.put("category", category);
        medicineValues.put("description", description);

        ContentValues stockValues = new ContentValues();
        stockValues.put("quantity", quantity);

        db.beginTransaction();

        try {

            int medicineRows = db.update(
                    "medicaments",
                    medicineValues,
                    "id = ?",
                    new String[]{String.valueOf(medicineId)}
            );

            int stockRows = db.update(
                    "pharmacy_medicaments",
                    stockValues,
                    "pharmacy_id = ? AND medicament_id = ?",
                    new String[]{
                            String.valueOf(pharmacyId),
                            String.valueOf(medicineId)
                    }
            );

            if (medicineRows > 0 && stockRows > 0) {

                db.setTransactionSuccessful();

                Toast.makeText(this,
                        "Modifications enregistrées",
                        Toast.LENGTH_SHORT).show();

                finish();

            } else {
                Toast.makeText(this,
                        "Impossible de modifier ce médicament",
                        Toast.LENGTH_SHORT).show();
            }

        } catch (Exception e) {

            Toast.makeText(this,
                    "Erreur : " + e.getMessage(),
                    Toast.LENGTH_LONG).show();

        } finally {
            db.endTransaction();
        }
    }
}
