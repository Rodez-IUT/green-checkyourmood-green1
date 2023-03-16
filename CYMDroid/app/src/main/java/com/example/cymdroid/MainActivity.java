package com.example.cymdroid;

import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.TextView;

import java.security.PrivateKey;

import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {

    private EditText adresseElectronique,
                     motDePasse;

    private TextView adresseElectroniqueError,
                     motDePasseError;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE); //will hide the title
        getSupportActionBar().hide(); // hide the title bar
        this.getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN); //enable full screen
        setContentView(R.layout.activity_main);

        adresseElectronique = findViewById(R.id.addresseElectronique);
        adresseElectroniqueError = findViewById(R.id.addresseElectroniqueError);

        motDePasse = findViewById(R.id.motDePasse);
        motDePasseError = findViewById(R.id.motDePasseError);
    }

    public void erreurSaisie(View view) {
        adresseElectroniqueError.setVisibility(View.VISIBLE);
        motDePasseError.setVisibility(View.VISIBLE);

        adresseElectronique.setBackgroundResource(R.drawable.edittext_error_style);
        motDePasse.setBackgroundResource(R.drawable.edittext_error_style);
    }
}