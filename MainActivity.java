package com.example.telephone;

import android.app.Activity;
import android.os.Bundle;
import android.widget.ListView;
import android.widget.TableRow;
import android.widget.TextView;

public class MainActivity extends Activity {
    int[] unicode = {0x1F60A, 0x1F601, 0x1F602, 0x1F643, 0x1F60D};
    String[][] texte = {{"Adoration", "14/12/2022"}};
    TextView[] emoji;
    TextView[] text1;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        text1 = new TextView[]{findViewById(R.id.text1), findViewById(R.id.text1Bis)};
        emoji = new TextView[]{findViewById(R.id.emoji1), findViewById(R.id.emoji2),
                findViewById(R.id.emoji3), findViewById(R.id.emoji4), findViewById(R.id.emoji5)};
        emoji[0].setText(getEmojiByUnicode(unicode[0]));
        emoji[1].setText(getEmojiByUnicode(unicode[1]));
        emoji[2].setText(getEmojiByUnicode(unicode[2]));
        emoji[3].setText(getEmojiByUnicode(unicode[3]));
        emoji[4].setText(getEmojiByUnicode(unicode[4]));
        text1[0].setText(texte[0][0]);
        text1[1].setText(texte[0][1]);
    }

    public String getEmojiByUnicode(int unicode){
        return new String(Character.toChars(unicode));
    }
}