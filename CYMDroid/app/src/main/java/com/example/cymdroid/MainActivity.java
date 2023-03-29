package com.example.cymdroid;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.security.PrivateKey;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.JsonRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity {

    private EditText adresseElectronique,
                     motDePasse;

    private TextView adresseElectroniqueError,
                     motDePasseError;

    private static final String URL_LOGIN = "http://10.0.2.2/API_REST/login/%s/%s";

    /** File d'attente pour les requêtes Web (en lien avec l'utilisation de Volley) */
    private RequestQueue fileRequete;

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

        // Gestion de la file d'attente des requêtes
        fileRequete = Volley.newRequestQueue(this);
    }

    public void connecter(View view) {

        adresseElectronique.setBackgroundResource(R.drawable.edittext_error_style);
        motDePasse.setBackgroundResource(R.drawable.edittext_error_style);


        try {
            // L'adresse mail saisi par l'utilisateur est récupéré et encodé en UTF-8
            //String adresseElectroniqueTxt = URLEncoder.encode(adresseElectronique.getText().toString(), "UTF-8");
            String adresseElectroniqueTxt = adresseElectronique.getText().toString();
            // Le mot de passe saisi par l'utilisateur est récupéré et encodé en UTF-8
            String motDePasseTxt = URLEncoder.encode(motDePasse.getText().toString(), "UTF-8");
            // Les informations renseignés par l'utilisateur sont insésrés dans l'URL de login
            String url = String.format(URL_LOGIN, adresseElectroniqueTxt, motDePasseTxt);
            Toast.makeText(this, url, Toast.LENGTH_LONG).show();
            /*
             * On crée une requête GET, paramètrée par l'url préparée ci-dessus,
             * Le résultat de cette requête sera un objet Json
             */

            JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.GET, url, null,
                    // écouteur de la réponse renvoyée par la requête
                    new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject reponse) {
                            // la zone de résultat est renseignée après extraction des
                            // types de clients
                            // Changement de page -> Direction sur la page de visualisation des humeurs
                            pageVisualisationHumeurs();
                        }
                    },
                    // écouteur du retour de la requête si aucun résultat n'est renvoyé
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError erreur) {
                            adresseElectroniqueError.setVisibility(View.VISIBLE);
                            motDePasseError.setVisibility(View.VISIBLE);
                            afficherToast(erreur.getMessage());
                        }
                    });

            // la requête est placée dans la file d'attente des requêtes
            fileRequete.add(requeteVolley);

        } catch(UnsupportedEncodingException erreur) {
            // problème lors de l'encodage de la chaîne titre
            Toast.makeText(this, R.string.message_erreur_encodage, Toast.LENGTH_LONG).show();
        }
    }

    public void afficherToast(String erreur) {
        Toast.makeText(this, erreur, Toast.LENGTH_LONG).show();
    }

    public void pageVisualisationHumeurs() {
        Intent switchActivityIntent = new Intent(this, OngletActivity.class);
        startActivity(switchActivityIntent);
    }
}