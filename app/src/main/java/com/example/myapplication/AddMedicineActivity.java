
package com.example.myapplication;

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

        // Initialisation des champs
        edtName = findViewById(R.id.edtMedicineName);
        edtDosage = findViewById(R.id.edtMedicineDosage);
        edtCategory = findViewById(R.id.edtMedicineCategory);
        edtQuantity = findViewById(R.id.edtMedicineQuantity);
        edtDescription = findViewById(R.id.edtMedicineDescription);

        btnSave = findViewById(R.id.btnSaveMedicine);

        databaseHelper = new DatabaseHelper(this);

        // Récupération de l'identifiant de la pharmacie
        pharmacyId = getIntent().getLongExtra("pharmacy_id", -1);

        if (pharmacyId <= 0) {
            Toast.makeText(this,
                    "Identifiant de pharmacie introuvable",
                    Toast.LENGTH_LONG).show();

            finish();
            return;
        }

        // Bouton Enregistrer
        btnSave.setOnClickListener(v -> saveMedicine());
    }

    private void saveMedicine() {

        // Récupération des valeurs
        String name = edtName.getText().toString().trim();
        String dosage = edtDosage.getText().toString().trim();
        String category = edtCategory.getText().toString().trim();
        String quantityText = edtQuantity.getText().toString().trim();
        String description = edtDescription.getText().toString().trim();

        // Vérification des champs obligatoires
        if (name.isEmpty() || dosage.isEmpty()
                || category.isEmpty() || quantityText.isEmpty()) {

            Toast.makeText(this,
                    "Veuillez remplir les champs obligatoires",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        // Vérification de la quantité
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

        // Vérification de l'identifiant
        if (pharmacyId <= 0) {
            Toast.makeText(this,
                    "Identifiant de pharmacie invalide",
                    Toast.LENGTH_LONG).show();
            return;
        }

        // Désactiver le bouton pendant l'enregistrement
        btnSave.setEnabled(false);

        // Ajouter le médicament et son stock
        String result = databaseHelper.addMedicine(
                pharmacyId,
                name,
                dosage,
                category,
                description,
                quantity
        );

        // Résultat de l'opération
        if ("SUCCESS".equals(result)) {

            Toast.makeText(this,
                    "Médicament ajouté avec succès !",
                    Toast.LENGTH_SHORT).show();

            finish();

        } else {

            Toast.makeText(this,
                    result,
                    Toast.LENGTH_LONG).show();

            btnSave.setEnabled(true);
        }
    }
}
