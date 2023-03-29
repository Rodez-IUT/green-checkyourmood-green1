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

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.Locale;

import androidx.fragment.app.Fragment;

public class AjoutActivity extends Fragment implements View.OnClickListener {


    final Calendar myCalendar= Calendar.getInstance();
    private EditText laDate;
    private EditText lHeure;
    private EditText infos;
    private Spinner spinner;

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
        //On récupère la vue (le layout) associée au fragment activity_ajout et les champs
        View vueDuFragment = inflater.inflate(R.layout.activity_ajout, container, false);

        laDate = vueDuFragment.findViewById(R.id.date);
        lHeure = vueDuFragment.findViewById(R.id.heure);
        spinner = vueDuFragment.findViewById(R.id.spinner);
        infos = vueDuFragment.findViewById(R.id.infos);
        vueDuFragment.findViewById(R.id.annuler).setOnClickListener(this);
        vueDuFragment.findViewById(R.id.valider).setOnClickListener(this);

        //Gestion du spinner des humeurs
        List<String> humeurs = new ArrayList<String>();
        humeurs.add(0, "Selectionner une humeur"); //Choix par défaut à garder
        humeurs.add("Joie");
        humeurs.add("Triste");
        ArrayAdapter<String> arrayAdapter = new ArrayAdapter(getContext(), android.R.layout.simple_list_item_1, humeurs);
        arrayAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(arrayAdapter);
        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (parent.getItemAtPosition(position).equals("Choose Football players from lis")){
                }else {
                    String item = parent.getItemAtPosition(position).toString();
                    Toast.makeText(parent.getContext(),"Selected: " +item, Toast.LENGTH_SHORT).show();
                }
            }
            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        //Gestion date
        DatePickerDialog.OnDateSetListener date =new DatePickerDialog.OnDateSetListener() {
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
        System.out.println(v.getId());
        if (v.getId() == R.id.valider) {
            // Clic sur le bouton valider

        } else {
            // Clic sur le bouton annuler
            lHeure.setText("");
            laDate.setText("");
            infos.setText("");
            spinner.setSelection(0);
        }
    }

    private void updateLabel(){
        String myFormat="dd/MM/yy";
        SimpleDateFormat dateFormat=new SimpleDateFormat(myFormat, Locale.FRENCH);
        laDate.setText(dateFormat.format(myCalendar.getTime()));
    }

}
