package com.arkipl.deyakagentbillinglite;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.telephony.PhoneStateListener;
import android.telephony.TelephonyManager;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;

import in.deyak.supporting.CommonFunction;
import in.deyak.supporting.DatabaseOperation;
import in.deyak.supporting.Rcrypt;
import in.deyak.supporting.TableData;
import in.deyak.supporting.myPhoneStateListener;


public class SurveyActivity extends AppCompatActivity {

    Context ctx = this;
    Activity act = this;

    CommonFunction cf = new CommonFunction();

    DatabaseOperation dbo;

    TelephonyManager telephonyManager;
    myPhoneStateListener psListener;



    ///Layout Variable//////
    EditText input_1_mobile, input_2_meterslno, input_4_mheight;
    Spinner  input_3_metertype,input_5_consumertype;
    TextView input_1_error;

    ///data variable///////
    String input_1_data, input_2_data, input_4_data;
    int input_3_data=0,  input_5_data=0;
    float gps_lati=0, gps_longi=0, gps_alti=0;
    int nwsignal;
    String conid;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_survey);

        Intent intent = getIntent();
        conid = intent.getStringExtra("id");


        input_1_mobile          = (EditText) this.findViewById(R.id.survey_field_mobno);
        input_2_meterslno       = (EditText) this.findViewById(R.id.survey_field_meterslno);
        input_3_metertype       = (Spinner) this.findViewById(R.id.survey_field_metertype);
        input_4_mheight         = (EditText) this.findViewById(R.id.survey_field_meterheight);
        input_5_consumertype    = (Spinner) this.findViewById(R.id.survey_field_consumertype);

        input_1_error   = (TextView) this.findViewById(R.id.survey_field_mobno_error);

        dbo = new DatabaseOperation(ctx);
        psListener = new myPhoneStateListener();

        telephonyManager = (TelephonyManager)getSystemService(Context.TELEPHONY_SERVICE);
        telephonyManager.listen(psListener, PhoneStateListener.LISTEN_SIGNAL_STRENGTHS);


        array_setup();
    }

    private void register_data(){
        input_1_data = input_1_mobile.getText().toString();
        input_2_data = input_2_meterslno.getText().toString();
        input_4_data = input_4_mheight.getText().toString();

        if(check_eligibility()){
            registration_complete();
        }
    }



    private void registration_complete() {
        nwsignal = psListener.signalStrengthValue;

        String[] col = new String[9];                                   String[] val = new String[9];
        col[0] = TableData.TableInfo.TABLE_MDATA_survey_gps_lati;       val[0] = Rcrypt.encode(DatabaseOperation.imeino,gps_lati + "");
        col[1] = TableData.TableInfo.TABLE_MDATA_survey_gps_longi;      val[1] = Rcrypt.encode(DatabaseOperation.imeino,gps_longi + "");
        col[2] = TableData.TableInfo.TABLE_MDATA_survey_gps_alti;       val[2] = Rcrypt.encode(DatabaseOperation.imeino,gps_alti + "");
        col[3] = TableData.TableInfo.TABLE_MDATA_survey_meterheight;    val[3] = Rcrypt.encode(DatabaseOperation.imeino,input_4_data + "");
        col[4] = TableData.TableInfo.TABLE_MDATA_survey_mobno;          val[4] = Rcrypt.encode(DatabaseOperation.imeino,input_1_data + "");
        col[5] = TableData.TableInfo.TABLE_MDATA_survey_meterslno;      val[5] = Rcrypt.encode(DatabaseOperation.imeino,input_2_data + "");
        col[6] = TableData.TableInfo.TABLE_MDATA_survey_metertype;      val[6] = Rcrypt.encode(DatabaseOperation.imeino,input_3_data + "");
        col[7] = TableData.TableInfo.TABLE_MDATA_survey_consumertype;   val[7] = Rcrypt.encode(DatabaseOperation.imeino,input_5_data + "");
        col[8] = TableData.TableInfo.TABLE_MDATA_survey_nwsignal;       val[8] = Rcrypt.encode(DatabaseOperation.imeino,nwsignal + "");

        DatabaseOperation dbo = new DatabaseOperation(ctx);
        dbo.updateInformation(dbo, TableData.TableInfo.TABLE_MDATA_NAME,col,val,new String[]{TableData.TableInfo.TABLE_MDATA_id},new String[]{conid});

        Intent readingintent = new Intent(act,ReadingActivity.class);
        readingintent.putExtra("id",conid);
        startActivity(readingintent);

    }




    /////////////////////////////////////////////////////////////////////////////////////////////



    private boolean check_eligibility(){
        boolean r = false;

        {
            input_1_mobile.setBackgroundResource(R.drawable.form_input_back);
            input_1_error.setText("");
        }

        //spl
        if((! input_1_data.isEmpty()) && input_1_data.length() !=10){input_1_mobile.setBackgroundResource(R.drawable.form_input_error); input_1_error.setText("Enter 10 digit mobile no");}

        if(input_1_data.isEmpty() || ((! input_1_data.isEmpty()) && input_1_data.length() == 10)) {
            r = true;
        }
        return r;
    }




    //array////////////////////////////////////////////////////////////////////////////
    ArrayAdapter input_3_adapter, input_5_adapter;
    private void array_setup(){
        //input 3
        input_3_adapter = new ArrayAdapter<String>(ctx,android.R.layout.simple_spinner_item,ctx.getResources().getStringArray(R.array.survey_metertype_array));
        input_3_adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        input_3_metertype.setAdapter(input_3_adapter);
        input_3_metertype.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                input_3_data = position;
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });

        //input 3
        input_5_adapter = new ArrayAdapter<String>(ctx,android.R.layout.simple_spinner_item,ctx.getResources().getStringArray(R.array.survey_consumertype_array));
        input_5_adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        input_5_consumertype.setAdapter(input_5_adapter);
        input_5_consumertype.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                input_5_data = position;
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });


    }



    ///////////////////////////////////////////////////////////////////////////////////
    @Override
    public void onBackPressed() {
        CommonFunction.makeToast(ctx,"Tap cross to close").show();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {

        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_survey, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_close) {
            this.finish();
            Intent consumerlist = new Intent(this, ConsumerActivity.class);
            consumerlist.putExtra("type","0");
            startActivity(consumerlist);
            return true;
        }else if (id == R.id.action_ok) {
            register_data();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        Runtime.getRuntime().gc();
    }

}
