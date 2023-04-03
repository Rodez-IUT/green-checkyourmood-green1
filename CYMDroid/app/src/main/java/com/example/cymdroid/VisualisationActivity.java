package com.example.cymdroid;

import android.app.AlertDialog;
import android.os.Bundle;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class VisualisationActivity extends Fragment implements AdapterView.OnItemClickListener {

    ListView liste;
    List<ItemHumeur> humeurs;

    private final String URL_HUMEURS = "http://10.0.2.2/API_REST/humeurs";

    /** File d'attente pour les requêtes Web (en lien avec l'utilisation de Volley) */
    private RequestQueue fileRequete;
    private String APIKey;
    private ItemHumeurAdapter item;

    public static VisualisationActivity newInstance() {
        VisualisationActivity fragment = new VisualisationActivity();
        return fragment;
    }
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState){
        View vueDuFragment = inflater.inflate(R.layout.activity_visualisation, container, false);

        // Gestion de la file d'attente des requêtes
        fileRequete = Volley.newRequestQueue(getActivity());

        liste = vueDuFragment.findViewById(R.id.liste);

        humeurs = new ArrayList<>();

        item = new ItemHumeurAdapter(this.getContext(), R.layout.vue_item_liste, humeurs);
        liste.setAdapter(item);
        liste.setOnItemClickListener(this);

        afficherHumeurs();

        // Declaring a layout (changes are to be made to this)
        // Declaring a textview (which is inside the layout)
        SwipeRefreshLayout swipeRefreshLayout = vueDuFragment.findViewById(R.id.refreshLayout);

        // Refresh  the layout
        swipeRefreshLayout.setOnRefreshListener(
                new SwipeRefreshLayout.OnRefreshListener() {
                    @Override
                    public void onRefresh() {

                        // Refresh des humeurs
                        afficherHumeurs();

                        // This line is important as it explicitly
                        // refreshes only once
                        // If "true" it implicitly refreshes forever
                        swipeRefreshLayout.setRefreshing(false);
                    }
                }
        );

        APIKey = ((OngletActivity) getActivity()).getAPIKey();


        return vueDuFragment;
    }

    public void afficherToast(String msg) {
        Toast.makeText(getActivity(), msg, Toast.LENGTH_LONG).show();
    }

    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
        onClickMood(i);
    }

    public void onClickMood(int i) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this.getContext());

        final View customLayout = getLayoutInflater().inflate(R.layout.alert_vue_description, null);
        TextView titre = customLayout.findViewById(R.id.alertTitre);
        titre.setText("Humeur");
        TextView description = customLayout.findViewById(R.id.alertDescription);
        description.setText(humeurs.get(i).getDescription());
        builder.setView(customLayout);
        AlertDialog dialog = builder.create();
        dialog.show();
        dialog.getWindow().setGravity(Gravity.BOTTOM);
        dialog.getWindow().setBackgroundDrawableResource(R.drawable.alerte_description_humeur);
    }

    private void afficherHumeurs() {

        /* Récupère les dernières humeurs grâce à l'API */
        JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET, URL_HUMEURS, null,
                // écouteur de la réponse renvoyée par la requête
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray reponse) {
                        // la zone de résultat est renseignée après extraction des
                        // types de clients
                        // Affiche des humeurs dans la liste
                        humeurs.clear();
                        for (int noHumeur = 0 ; noHumeur < reponse.length() ; noHumeur++) {
                            try {
                                humeurs.add(noHumeur, new ItemHumeur(reponse.getJSONObject(noHumeur).getString("Emoji"),
                                        reponse.getJSONObject(noHumeur).getString("Libelle"),
                                        reponse.getJSONObject(noHumeur).getString("Date_Hum"),
                                        reponse.getJSONObject(noHumeur).getString("Informations")
                                ));
                            } catch (JSONException e) {
                            }
                        }

                        item.notifyDataSetChanged();
                        liste.requestLayout();
                    }
                },
                // écouteur du retour de la requête si aucun résultat n'est renvoyé
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError erreur) {
                    afficherToast(erreur.getMessage());
                }
            }){
            public Map<String, String> getHeaders() throws AuthFailureError {

                HashMap header = new HashMap();
                header.put("Content-Type", "application/json");
                header.put("APIKEYDEMONAPI", APIKey);
                return header;
            }
        };

        // la requête est placée dans la file d'attente des requêtes
        fileRequete.add(requeteVolley);
    }
}