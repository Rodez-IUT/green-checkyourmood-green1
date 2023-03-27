package com.example.cymdroid;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.fragment.app.Fragment;

public class AjoutActivity extends Fragment implements View.OnClickListener {

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
            //On récupère la vue (le layout) associée au fragment un
            View vueDuFragment = inflater.inflate(R.layout.activity_ajout, container, false);

            return vueDuFragment;
    }

    @Override
    public void onClick(View v){ }
}
