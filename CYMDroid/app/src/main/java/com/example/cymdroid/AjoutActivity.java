package com.example.cymdroid;

import android.app.AppComponentFactory;
import android.app.DatePickerDialog;
import android.app.TimePickerDialog;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TimePicker;
import android.widget.Toast;

import java.io.UnsupportedEncodingException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

import androidx.fragment.app.Fragment;

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

public class AjoutActivity extends Fragment implements View.OnClickListener {


    final Calendar myCalendar= Calendar.getInstance();
    private EditText laDate;
    private EditText lHeure;
    private EditText infos;
    private Spinner spinner;

    private String APIKey;

    private final String FORMAT_DATE = "%s %s";

    private final String URL_HUMEUR = "http://10.0.2.2/API_REST/humeur";

    private final String URL_LISTE_HUMEURS = "http://10.0.2.2/API_REST/listeHumeurs";

    /** File d'attente pour les requêtes Web (en lien avec l'utilisation de Volley) */
    private RequestQueue fileRequete;

    public AjoutActivity() {
        // Required empty public constructor
    }


    public static AjoutActivity newInstance() {
        AjoutActivity fragment = new AjoutActivity();
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        APIKey = ((OngletActivity) getActivity()).getAPIKey();

        //On récupère la vue (le layout) associée au fragment activity_ajout et les champs
        View vueDuFragment = inflater.inflate(R.layout.activity_ajout, container, false);

        // Gestion de la file d'attente des requêtes
        fileRequete = Volley.newRequestQueue(getActivity());

        laDate = vueDuFragment.findViewById(R.id.date);
        lHeure = vueDuFragment.findViewById(R.id.heure);
        spinner = vueDuFragment.findViewById(R.id.spinner);
        infos = vueDuFragment.findViewById(R.id.infos);
        vueDuFragment.findViewById(R.id.annuler).setOnClickListener(this);
        vueDuFragment.findViewById(R.id.valider).setOnClickListener(this);

        //Gestion du spinner des humeurs
        List<String> humeurs = new ArrayList<String>();
        humeurs.add(0, "Selectionner une humeur"); //Choix par défaut à garder
        JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET, URL_LISTE_HUMEURS, null,
                // écouteur de la réponse renvoyée par la requête
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray reponse) {
                        // la zone de résultat est renseignée après extraction des
                        // types de clients
                        // Affiche des humeurs dans la liste

                        for (int noHumeur = 0 ; noHumeur < reponse.length() ; noHumeur++) {
                            try {
                                humeurs.add(noHumeur + 1, reponse.getJSONObject(noHumeur).getString("Libelle"));
                            } catch (JSONException e) {
                            }
                        }
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
        ArrayAdapter<String> arrayAdapter = new ArrayAdapter(getContext(), android.R.layout.simple_list_item_1, humeurs);
        arrayAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(arrayAdapter);
        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (parent.getItemAtPosition(position).equals("Choose Football players from lis")){
                } else {
                    String item = parent.getItemAtPosition(position).toString();
                }
            }
            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        //Gestion date
        DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int month, int day) {
                myCalendar.set(Calendar.YEAR, year);
                myCalendar.set(Calendar.MONTH,month);
                myCalendar.set(Calendar.DAY_OF_MONTH,day);
                updateLabel();
            }
        };
        laDate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                new DatePickerDialog(getContext(),date,myCalendar.get(Calendar.YEAR),myCalendar.get(Calendar.MONTH),myCalendar.get(Calendar.DAY_OF_MONTH)).show();
            }
        });

        //Gestion de l'heure
        lHeure.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Calendar myCalendar = Calendar.getInstance();
                int hour = myCalendar.get(Calendar.HOUR_OF_DAY);
                int minute = myCalendar.get(Calendar.MINUTE);

                // time picker dialog
                TimePickerDialog mTimePicker;
                mTimePicker = new TimePickerDialog(getContext(), new TimePickerDialog.OnTimeSetListener() {
                    @Override
                    public void onTimeSet(TimePicker timePicker, int selectedHour, int selectedMinute) {
                        lHeure.setText( selectedHour + ":" + selectedMinute);
                    }
                }, hour, minute, true); //Yes 24-hour time mode
                mTimePicker.setTitle("Select Time");
                mTimePicker.show();
            }
        });


        return vueDuFragment;
    }

    @Override
    public void onClick(View v){

        if (v.getId() == R.id.valider) {
            // Clic sur le bouton valider

            try {
                // Test si aucune humeur n'a été sélectionnée
                if (spinner.getSelectedItemPosition() != 0) {
                    JSONObject infosHumeurs = new JSONObject();
                    infosHumeurs.put("ID_HUMEUR", spinner.getSelectedItemPosition());
                    String dateHeure = String.format(FORMAT_DATE, laDate.getText(), lHeure.getText());
                    infosHumeurs.put("DATE_HUMEUR", dateHeure);
                    infosHumeurs.put("INFO", infos.getText());
                    JsonObjectRequest requeteVolley = new JsonObjectRequest(Request.Method.POST, URL_HUMEUR, infosHumeurs,
                            // écouteur de la réponse renvoyée par la requête
                            new Response.Listener<JSONObject>() {
                                @Override
                                public void onResponse(JSONObject reponse) {
                                    // la zone de résultat est renseignée après extraction des
                                    // types de clients
                                    // Affiche des humeurs dans la liste

                                    afficherToast("Humeur correctement ajoutée");
                                    remiseDefaut();
                                }
                            },
                            // écouteur du retour de la requête si aucun résultat n'est renvoyé
                            new Response.ErrorListener() {
                                @Override
                                public void onErrorResponse(VolleyError erreur) {
                                    afficherToast("Connexion impossible");
                                }
                            }) {
                        public Map<String, String> getHeaders() throws AuthFailureError {

                            HashMap header = new HashMap();
                            header.put("APIKEYDEMONAPI", APIKey);
                            return header;
                        }
                    };
                    // la requête est placée dans la file d'attente des requêtes
                    fileRequete.add(requeteVolley);
                } else {
                    afficherToast("Aucune humeur sélectionnée");
                }
            } catch (Exception e) {

                afficherToast("Erreur de conversion");
            }

        } else {
            // Clic sur le bouton annuler
            remiseDefaut();
        }
    }

    private void updateLabel(){
        String myFormat="yyyy-MM-dd";
        SimpleDateFormat dateFormat=new SimpleDateFormat(myFormat, Locale.FRENCH);
        laDate.setText(dateFormat.format(myCalendar.getTime()));
    }

    public void afficherToast(String msg) {
        Toast.makeText(getActivity(), msg, Toast.LENGTH_LONG).show();
    }

    private void remiseDefaut() {
        spinner.setSelection(0);
        laDate.setText("");
        lHeure.setText("");
        infos.setText("");
    }
}
