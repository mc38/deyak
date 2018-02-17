package com.arkipl.deyakagentbillinglite;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TextView;

import com.google.config.Config;
import com.google.remote.RemoteAddress;
import com.google.remote.RemoteConnection;
import com.google.remote.ResponseAction;

import java.util.ArrayList;

import in.deyak.supporting.BillProcess;
import in.deyak.supporting.CommonFunction;
import in.deyak.supporting.DatabaseOperation;
import in.deyak.supporting.Rcrypt;
import in.deyak.supporting.TableData;

import static in.deyak.supporting.DatabaseOperation.imeino;
import static in.deyak.supporting.DatabaseOperation.key;


public class CalculationCheckActivity extends AppCompatActivity {

    Context ctx = this;
    Menu m;

    CommonFunction cf;
    DatabaseOperation dbo;

    Spinner SELECT_upload, SELECT_cate;
    LinearLayout L_calc, L_veri;
    LinearLayout PROGRESS_holder,UPLOAD_holder,PROGRESS_holder_v,UPLOAD_holder_v;
    TextView PROGRESS_text, PROGRESS_text_v;
    ProgressBar PROGRESS_bar, PROGRESS_bar_v;
    ImageView UPLOAD_Button, UPLOAD_Button_v;
    EditText CALC_upload, CALC_reading, CALC_consumpday;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_calculation_check);

        cf = new CommonFunction();
        dbo = new DatabaseOperation(ctx);

        all_action();
    }



    Cursor mCR;
    private int uremain(){
        DatabaseOperation dbo = new DatabaseOperation(ctx);
        mCR = dbo.selectsqlInformation(dbo,"select * from "+ TableData.TableInfo.TABLE_MDATA_NAME);
        mCR.moveToFirst();

        return mCR.getCount();
    }

    int reading =0; int consumpday = 28; String scate="";
    int mcount = 0; int D_total=0;
    private void all_action(){
        SELECT_upload = (Spinner) this.findViewById(R.id.calc_select);

        L_calc = (LinearLayout) this.findViewById(R.id.calc_check);
        L_veri = (LinearLayout) this.findViewById(R.id.calc_verification);

        PROGRESS_holder = (LinearLayout) this.findViewById(R.id.progress_holder);
        PROGRESS_text = (TextView) this.findViewById(R.id.progress_text);
        PROGRESS_bar = (ProgressBar) this.findViewById(R.id.progress_bar);

        UPLOAD_holder = (LinearLayout) this.findViewById(R.id.upload_button_holder);
        UPLOAD_Button = (ImageView) this.findViewById(R.id.upload_button);

        CALC_reading = (EditText) this.findViewById(R.id.calcreading);
        CALC_consumpday = (EditText) this.findViewById(R.id.calcconsumptionday);
        CALC_upload = (EditText) this.findViewById(R.id.calcupload);

        /*----------------------------------------------------------------------------------------*/
        PROGRESS_holder_v = (LinearLayout) this.findViewById(R.id.v_progress_holder);
        UPLOAD_holder_v = (LinearLayout) this.findViewById(R.id.v_upload_button_holder);

        PROGRESS_text_v = (TextView) this.findViewById(R.id.v_progress_text);
        PROGRESS_bar_v = (ProgressBar) this.findViewById(R.id.v_progress_bar);

        SELECT_cate = (Spinner) this.findViewById(R.id.v_upload_cate);
        UPLOAD_Button_v = (ImageView) this.findViewById(R.id.v_upload_button);
        /*----------------------------------------------------------------------------------------*/
        setProgresBar(0,0);
        setProgresBar(0,1);
        /*----------------------------------------------------------------------------------------*/

        final ArrayList<String> cate_arr = new ArrayList<String>();
        final DatabaseOperation catedb = new DatabaseOperation(ctx);
        Cursor cateCR = catedb.selectsqlInformation(catedb, "SELECT " + TableData.TableInfo.TABLE_MDATA_consumer_category + " from " + TableData.TableInfo.TABLE_MDATA_NAME);
        if (cateCR.getCount() > 0) {
            cateCR.moveToFirst();
            while(cateCR.moveToNext()){
                String cate = Rcrypt.decode(catedb.key,cateCR.getString(0));
                if(! cate_arr.contains(cate)){
                    cate_arr.add(cate);
                }
            }

            scate = cate_arr.get(0).toString();
            ArrayAdapter<String> dtradapter = new ArrayAdapter<String>(ctx,android.R.layout.simple_list_item_1,cate_arr);
            SELECT_cate.setAdapter(dtradapter);
            SELECT_cate.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                @Override
                public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                    scate = cate_arr.get(position).toString();
                }

                @Override
                public void onNothingSelected(AdapterView<?> parent) {

                }
            });

        }

        /*----------------------------------------------------------------------------------------*/


        String[] select_upload_arr = new String[]{"Select One","Calculation Check","Billing Verification"};
        ArrayAdapter<String> su = new ArrayAdapter<String>(ctx,android.R.layout.simple_spinner_item,select_upload_arr);
        su.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        SELECT_upload.setAdapter(su);
        SELECT_upload.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if(position == 0){
                    L_calc.setVisibility(View.GONE); L_veri.setVisibility(View.GONE);
                }else if(position == 1){
                    L_calc.setVisibility(View.VISIBLE); L_veri.setVisibility(View.GONE);
                }else if(position == 2){
                    L_calc.setVisibility(View.GONE); L_veri.setVisibility(View.VISIBLE);
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });

        UPLOAD_Button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
            String calcreading = CALC_reading.getText().toString();
            if(! calcreading.isEmpty()){
                reading = Integer.valueOf(calcreading);
            }

            String calcday = CALC_consumpday.getText().toString();
            if(! calcday.isEmpty()){
                consumpday = Integer.valueOf(consumpday);
            }

            String calcupload = CALC_upload.getText().toString();
            if(! calcupload.isEmpty()){
                D_total = Integer.valueOf(calcupload);
            }

            CALC_reading.setText("");
            CALC_consumpday.setText("");
            CALC_upload.setText("");

            upload_start(0);
            }
        });

        UPLOAD_Button_v.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                upload_start(1);
            }
        });
    }


    Cursor vCR;
    String[] v_read = new String[]{"-1","0","1","5","10","50","100","500","1000","5000","10000","50000","100000","500000","1000000"};
    private void upload_start(int t){
        if(t==0) {
            if (D_total == 0) {
                D_total = uremain();
            } else {
                uremain();
            }

            if (D_total > mcount) {
                cicon_show(false);

                UPLOAD_holder.setVisibility(View.GONE);
                PROGRESS_holder.setVisibility(View.VISIBLE);

                int pct = (int) Math.floor(((mcount) * 100) / D_total);
                setProgresBar(pct,t);

                mCR.moveToPosition(mcount);
                reading_process(mCR,t);

            } else {
                upload_reset("All consumer data uploaded",t);
            }
        }else if(t==1){

            DatabaseOperation dbo = new DatabaseOperation(ctx);
            vCR = dbo.selectsqlInformation(dbo,"select * from "+ TableData.TableInfo.TABLE_MDATA_NAME +" where "+ TableData.TableInfo.TABLE_MDATA_consumer_category +"='"+ Rcrypt.encode(imeino,scate) +"'");
            if(vCR.getCount() >0) {
                vCR.moveToFirst();

                if (D_total == 0) {
                    D_total = v_read.length;
                }

                if (D_total > mcount) {
                    cicon_show(false);

                    UPLOAD_holder_v.setVisibility(View.GONE);
                    PROGRESS_holder_v.setVisibility(View.VISIBLE);

                    int pct = (int) Math.floor(((mcount) * 100) / D_total);
                    setProgresBar(pct,t);

                    reading_process(vCR, t);

                } else {
                    upload_reset("All consumer data uploaded", t);
                }
            }else{
                upload_reset("No consumer found of "+ scate +" category", t);
            }
        }
    }


    private void upload_reset(String msg, int t){
        if(t==0) {
            PROGRESS_holder.setVisibility(View.GONE);
            UPLOAD_holder.setVisibility(View.VISIBLE);
            mcount = 0; D_total=0;
            mCR.moveToFirst();
            reading = 0; consumpday = 28;
        }else if(t==1){
            PROGRESS_holder_v.setVisibility(View.GONE);
            UPLOAD_holder_v.setVisibility(View.VISIBLE);
            mcount = 0; D_total=0;
            reading = 0; consumpday = 28;
        }



        new AlertDialog.Builder(ctx)
                .setIcon(R.drawable.ic_action_notification)
                .setTitle("Notification")
                .setMessage(msg)
                .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.cancel();
                        dialog.dismiss();
                        cicon_show(true);
                    }
                })
                .setCancelable(false)
                .show();
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////

    private void reading_process(final Cursor cr, int t){
        DatabaseOperation aDB = new DatabaseOperation(ctx);
        final Cursor aCR = aDB.selectsqlInformation(aDB, "select * from "+ TableData.TableInfo.TABLE_SYSTEM_NAME);
        aCR.moveToFirst();

        reading_analysis(cr, aCR, t);
    }


    private void reading_analysis(Cursor CR, Cursor aCR, int tval) {
        String conno        = Rcrypt.decode(key,CR.getString(10));

        String rsrvunit_r   = Rcrypt.decode(key,CR.getString(22));
        String predate_r    = Rcrypt.decode(key,CR.getString(23));
        String prread       = Rcrypt.decode(key,CR.getString(24));
        String mfactor      = Rcrypt.decode(key,CR.getString(19));
        String adjustment   = Rcrypt.decode(key,CR.getString(30));
        String con_cate     = Rcrypt.decode(key,CR.getString(17));
        int pmstat          = Integer.valueOf(Rcrypt.decode(key,CR.getString(36)));

        long predate = Integer.valueOf(predate_r);

        /***billno**************************************************************/
        final long timestamp = cf.convert_to_timestamp(cf.CurrentDateTime());
        String bcode = Rcrypt.decode(key, aCR.getString(4));
        int subdiv = 1000 + Integer.valueOf(Rcrypt.decode(key, aCR.getString(7)));
        int dtrcode = 1000 + Integer.valueOf(Rcrypt.decode(key, CR.getString(8)));
        final String billno =  subdiv +""+ dtrcode +""+ bcode +""+ timestamp;

        /***expected_billdate**************************************************************/
        //String pdate = cf.convert_to_date(Long.valueOf(predate_r));
        //int billdate = (int) cf.convert_to_timestamp(cf.date_nxt(pdate,consumpday));
        int billdate = (int) timestamp;

        /***expected_reading**************************************************************/
        int reading = (int) cf.currency_round(Math.abs(Float.valueOf(rsrvunit_r) * consumpday),0);
        int unit_consump = (int) Math.floor(reading + cf.randomno(0,10));

        /*****************************************************************/
        String curmeterreading = "-1";
        int t = 1;
        if(tval==0) {
            if (pmstat == 0) {
                //curmeterreading = String.valueOf((int) Math.floor(Integer.valueOf(prread) + unit_consump));
                curmeterreading = prread;
                t = 0;
            }
        }else if(tval == 1){
            int new_reading = Math.abs(Integer.parseInt(v_read[mcount]) + Integer.valueOf(prread));
            curmeterreading = new_reading +""; pmstat = 0; t=0;
            if(Integer.valueOf(v_read[mcount])<0){
                pmstat = 5; t=1;
            }
            Log.d("calc", v_read[mcount] +" -> "+ curmeterreading +" -> "+ t +" -> "+ pmstat);
        }


        ArrayList<String> bdata = BillProcess.ReadingProcess(CR,t,aCR,billdate+"", curmeterreading, pmstat+"","");
        String apdcl_billno         = bdata.get(0);
        String pf                   = bdata.get(1);
        String consumption_day      = bdata.get(2);
        String unit_consumed        = bdata.get(3);
        String unit_pf              = bdata.get(4);
        String unit_billed          = bdata.get(5);
        String due_date             = bdata.get(6);

        String eng_brkup            = bdata.get(7);
        String energy_chrg          = bdata.get(8);
        String subsidy              = bdata.get(9);
        String total_energy_chrg    = bdata.get(10);
        String fixed_chrg           = bdata.get(11);
        String meter_rent           = bdata.get(12);
        String eduty                = bdata.get(13);
        String fppa                 = bdata.get(14);
        String current_demand       = bdata.get(15);
        String pa                   = bdata.get(16);
        String as                   = bdata.get(17);
        String cs                   = bdata.get(18);
        String total_arrear         = bdata.get(19);
        String net_bill_amount      = bdata.get(20);
        String net_bill_amount_dd   = bdata.get(21);

        //upload_reset(amounts);

        String data_send = "";
        data_send += conno +",";
        data_send += cf.convert_to_date(predate).substring(0,10) +",";
        data_send += prread +",";
        data_send += cf.convert_to_date(billdate).substring(0,10) +",";
        data_send += curmeterreading +",";
        data_send += unit_consumed +",";
        data_send += pf +",";
        data_send += unit_pf +",";
        data_send += mfactor +",";
        data_send += unit_billed +",";
        data_send += consumption_day +",";
        data_send += cf.convert_to_date(Long.valueOf(due_date)).substring(0,10) +",";
        data_send += eng_brkup.replace(",","->") +",";
        data_send += energy_chrg +",";
        data_send += subsidy +",";
        data_send += total_energy_chrg +",";
        data_send += fixed_chrg +",";
        data_send += meter_rent +",";
        data_send += eduty +",";
        data_send += fppa +",";
        data_send += current_demand +",";
        data_send += pa +",";
        data_send += as +",";
        data_send += cs +",";
        data_send += total_arrear +",";
        data_send += adjustment +",";
        data_send += net_bill_amount +",";
        data_send += net_bill_amount_dd +"";

        //upload_reset(data_send);
        dataupload(data_send,tval);

    }


    int tempconnno = 0, totaltempconnno = 40, tempconnwait = 1000;
    private void dataupload(final String dstr, final int tval){
        tempconnwait = (int) Math.floor(RemoteAddress.AddressInfo.responseTimeOut + (cf.randomno(10,20) *1000));

        String ra="";
        if(tval==0) {
            ra = RemoteAddress.AddressInfo.HOST + Config.getAccessURL(ctx) + RemoteAddress.AddressInfo.DATA_DEVLINK;
        }else if(tval==1){
            ra = RemoteAddress.AddressInfo.HOST + Config.getAccessURL(ctx) + RemoteAddress.AddressInfo.DATA_VRILINK;
        }

        final String c = cf.getCode(12);
        ArrayList<String> d = new ArrayList<String>();
        d.add(0, dstr);
        d.add(1, mcount+"");
        d.add(2, D_total+"");

        RemoteConnection rconn = new RemoteConnection(ctx);
        rconn.authsenddata(ra, d, new ResponseAction() {
            @Override
            public void onSuccessAction(String r) {

                if(r.isEmpty()){
                    upload_reset("Server Problem", tval);
                }else{
                    if(r.equals("1")) {
                        Log.d("File Upload Dev",mcount +" / "+ D_total +" ("+ tempconnno +") -> "+ dstr);
                        mcount++;
                        if(tempconnno < totaltempconnno) {
                            upload_start(tval);
                            tempconnno++;
                        }else {
                            tempconnno = 0;
                            new android.os.Handler().postDelayed(
                                    new Runnable() {
                                        public void run() {
                                            upload_start(tval);
                                        }
                                    }, tempconnwait);
                        }

                    }else{
                        upload_reset("Something fishy happening in the server.",tval);
                    }
                }
            }

            @Override
            public void onFailureAction(int code) {

                new AlertDialog.Builder(ctx)
                        .setIcon(R.drawable.ic_action_notification)
                        .setTitle("Notification")
                        .setMessage("Internet or Data Connection Problem")
                        .setPositiveButton("Retry", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                dialog.cancel();
                                dialog.dismiss();
                                tempconnno = 0;
                                new android.os.Handler().postDelayed(
                                        new Runnable() {
                                            public void run() {
                                                dataupload(dstr,tval);
                                            }
                                        }, tempconnwait);
                            }
                        })
                        .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                dialog.cancel();
                                dialog.dismiss();
                                upload_reset("Dev data upload cancelled",tval);
                            }
                        })
                        .setCancelable(false)
                        .show();
            }
        });
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////
    @Override
    public void onBackPressed() {
        CommonFunction.makeToast(ctx,"Tap cross to close").show();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {

        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_report, menu);
        m = menu;
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_close) {
            Intent login = new Intent(ctx, LoginActivity.class);
            this.finish();
            startActivity(login);
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        Runtime.getRuntime().gc();
    }

    public void cicon_show(boolean sh) {
        if (m != null) {
            MenuItem item = m.findItem(R.id.action_close);
            item.setVisible(sh);
        }
    }

    private void setProgresBar(int d, int t){
        String txt = d + "% (" + mcount + " / " + D_total + ") Please Wait... Uploading";
        if(t==0) {
            PROGRESS_bar.setProgress(d);
            PROGRESS_text.setText(txt);
        }else if(t==1){
            PROGRESS_bar_v.setProgress(d);
            PROGRESS_text_v.setText(txt);
        }

    }
}
