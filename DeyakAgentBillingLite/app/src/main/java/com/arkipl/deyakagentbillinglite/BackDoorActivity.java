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
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import java.util.ArrayList;

import in.deyak.supporting.BillProcess;
import in.deyak.supporting.CommonFunction;
import in.deyak.supporting.DatabaseOperation;
import in.deyak.supporting.Rcrypt;
import in.deyak.supporting.TableData;

import static in.deyak.supporting.DatabaseOperation.key;
import static in.deyak.supporting.DatabaseOperation.imeino;

public class BackDoorActivity extends AppCompatActivity {

    Context ctx = this;
    Menu m;

    CommonFunction cf;
    DatabaseOperation dbo;

    LinearLayout PROGRESS_holder,UPLOAD_holder;
    TextView PROGRESS_text;
    ProgressBar PROGRESS_bar;
    ImageView UPLOAD_Button;
    EditText CALC_consumpday, CALC_consumpunit;

    int D_total = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_back_door);

        cf = new CommonFunction();
        dbo = new DatabaseOperation(ctx);

        D_total = uremain();

        all_action();
    }

    Cursor mCR;
    private int uremain(){
        mCR = dbo.selectsqlInformation(dbo,"select * from "+ TableData.TableInfo.TABLE_MDATA_NAME +" where "+ TableData.TableInfo.TABLE_MDATA_n_status + "='' or " + TableData.TableInfo.TABLE_MDATA_n_status + " is NULL");
        if(mCR !=null) {
            mCR.moveToFirst();
            return mCR.getCount();
        }else{
            return -1;
        }
    }

    int consumpday = 28; int consumpunit = 0;
    private void all_action(){

        PROGRESS_holder = (LinearLayout) this.findViewById(R.id.progress_holder);
        PROGRESS_text = (TextView) this.findViewById(R.id.progress_text);
        PROGRESS_bar = (ProgressBar) this.findViewById(R.id.progress_bar);
        setProgresBar(0);

        UPLOAD_holder = (LinearLayout) this.findViewById(R.id.upload_button_holder);
        UPLOAD_Button = (ImageView) this.findViewById(R.id.upload_button);

        CALC_consumpday     = (EditText) this.findViewById(R.id.calcconsumptionday);
        CALC_consumpunit    = (EditText) this.findViewById(R.id.calcconsumptionunit);

        UPLOAD_Button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                String calcday = CALC_consumpday.getText().toString();
                if(! calcday.isEmpty()){
                    consumpday = Integer.valueOf(calcday);
                }

                String calcunit = CALC_consumpunit.getText().toString();
                if(! calcunit.isEmpty()){
                    consumpunit = Integer.valueOf(calcunit);
                }

                CALC_consumpday.setText("");
                CALC_consumpunit.setText("");
                mCR.moveToFirst();
                upload_start();
            }
        });

    }


    int D_i =0;
    private void upload_start(){
        if(D_i<D_total) {
            cicon_show(false);

            UPLOAD_holder.setVisibility(View.GONE);
            PROGRESS_holder.setVisibility(View.VISIBLE);

            //int pct = (int) Math.floor(((D_total - uremain())*100)/D_total);
            int pct = (int) Math.floor(((D_i)*100)/D_total);
            setProgresBar(pct);

            mCR.moveToPosition(D_i);
            reading_process(mCR);
            D_i++;
        }else{
            upload_reset("All consumer data uploaded");
        }
        /*
        else if(uremain()<0){

            dbo.close();
            dbo = new DatabaseOperation(ctx);
            upload_start();
        }
        */
    }


    private void upload_reset(String msg){
        PROGRESS_holder.setVisibility(View.GONE);
        UPLOAD_holder.setVisibility(View.VISIBLE);

        mCR.moveToFirst();
        consumpday = 28;
        consumpunit = 0;

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

    private void reading_process(final Cursor cr){
        DatabaseOperation aDB = new DatabaseOperation(ctx);
        final Cursor aCR = aDB.selectsqlInformation(aDB, "select * from "+ TableData.TableInfo.TABLE_SYSTEM_NAME);
        aCR.moveToFirst();

        reading_analysis(cr, aCR);
    }


    private void reading_analysis(Cursor CR, Cursor aCR) {
        String aid          = aCR.getString(0);
        String conid        = CR.getString(0);

        String rsrvunit_r   = Rcrypt.decode(key,CR.getString(22));
        String prread       = Rcrypt.decode(key,CR.getString(24));
        String con_cate     = Rcrypt.decode(key,CR.getString(17));
        int pmstat          = Integer.valueOf(Rcrypt.decode(key,CR.getString(36)));

        Log.d("CONID", D_i +" -> "+ Rcrypt.decode(key,conid)+" -> "+ pmstat);

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
        //int reading = (int) cf.currency_round(Math.abs(Float.valueOf(rsrvunit_r) * consumpday),0);
        int reading = consumpunit;
        int unit_consump = (int) reading;

        /*****************************************************************/

        String curmeterreading = "-1"; int t = 1;
        if(pmstat==0) {
            curmeterreading = String.valueOf((int) Math.floor(Integer.valueOf(prread) + unit_consump));
            //curmeterreading = prread;
            t =0;
        }

        ArrayList<String> bdata = BillProcess.ReadingProcess(CR,t,aCR,billdate+"",curmeterreading,pmstat+"","");
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


        if(Integer.valueOf(consumption_day) <1000){

            String[] col,val;
            col = new String[30];                                                       val = new String[30];
            col[0]  = TableData.TableInfo.TABLE_MDATA_n_billno;                         val[0]  = Rcrypt.encode(imeino,billno);
            col[1]  = TableData.TableInfo.TABLE_MDATA_n_status;                         val[1]  = Rcrypt.encode(imeino,pmstat +"");
            col[2]  = TableData.TableInfo.TABLE_MDATA_n_reading_date;                   val[2]  = Rcrypt.encode(imeino,billdate +"");
            col[3]  = TableData.TableInfo.TABLE_MDATA_n_postmeter_read;                 val[3]  = Rcrypt.encode(imeino,curmeterreading +"");
            col[4]  = TableData.TableInfo.TABLE_MDATA_n_meterpic;                       val[4]  = Rcrypt.encode(imeino,"0");
            col[5]  = TableData.TableInfo.TABLE_MDATA_n_meterpic_binary;                val[5]  = Rcrypt.encode(imeino,"0");
            col[6]  = TableData.TableInfo.TABLE_MDATA_n_unit_consumed;                  val[6]  = Rcrypt.encode(imeino,unit_consumed +"");
            col[7]  = TableData.TableInfo.TABLE_MDATA_n_unit_billed;                    val[7]  = Rcrypt.encode(imeino,unit_billed +"");
            col[8]  = TableData.TableInfo.TABLE_MDATA_n_consumption_day;                val[8]  = Rcrypt.encode(imeino,consumption_day +"");
            col[9]  = TableData.TableInfo.TABLE_MDATA_n_due_date;                       val[9]  = Rcrypt.encode(imeino,due_date +"");
            col[10] = TableData.TableInfo.TABLE_MDATA_n_energy_brkup;                   val[10] = Rcrypt.encode(imeino,eng_brkup +"");
            col[11] = TableData.TableInfo.TABLE_MDATA_n_energy_amount;                  val[11] = Rcrypt.encode(imeino,energy_chrg +"");
            col[12] = TableData.TableInfo.TABLE_MDATA_n_subsidy;                        val[12] = Rcrypt.encode(imeino,subsidy +"");
            col[13] = TableData.TableInfo.TABLE_MDATA_n_total_energy_charge;            val[13] = Rcrypt.encode(imeino,total_energy_chrg +"");
            col[14] = TableData.TableInfo.TABLE_MDATA_n_fixed_charge;                   val[14] = Rcrypt.encode(imeino,fixed_chrg +"");
            col[15] = TableData.TableInfo.TABLE_MDATA_n_electricity_duty;               val[15] = Rcrypt.encode(imeino,eduty +"");
            col[16] = TableData.TableInfo.TABLE_MDATA_n_fppa_charge;                    val[16] = Rcrypt.encode(imeino,fppa +"");
            col[17] = TableData.TableInfo.TABLE_MDATA_n_current_demand;                 val[17] = Rcrypt.encode(imeino,current_demand +"");
            col[18] = TableData.TableInfo.TABLE_MDATA_n_total_arrear;                   val[18] = Rcrypt.encode(imeino,total_arrear +"");
            col[19] = TableData.TableInfo.TABLE_MDATA_n_net_bill_amount;                val[19] = Rcrypt.encode(imeino,net_bill_amount +"");
            col[20] = TableData.TableInfo.TABLE_MDATA_n_net_bill_amount_after_duedate;  val[20] = Rcrypt.encode(imeino,net_bill_amount_dd +"");
            col[21] = TableData.TableInfo.TABLE_MDATA_n_gps_verification;               val[21] = Rcrypt.encode(imeino,"0");
            col[22] = TableData.TableInfo.TABLE_MDATA_n_ocr_analysis;                   val[22] = Rcrypt.encode(imeino,"0");
            col[23] = TableData.TableInfo.TABLE_MDATA_n_pf;                             val[23] = Rcrypt.encode(imeino,pf+"");
            col[24] = TableData.TableInfo.TABLE_MDATA_aid;                              val[24] = aid;
            col[25] = TableData.TableInfo.TABLE_MDATA_n_meter_rent;                     val[25] = Rcrypt.encode(imeino,meter_rent +"");
            col[26] = TableData.TableInfo.TABLE_MDATA_n_current_surcharge;              val[26] = Rcrypt.encode(imeino, cs +"");
            col[27] = TableData.TableInfo.TABLE_MDATA_n_unit_pf;                        val[27] = Rcrypt.encode(imeino, unit_pf +"");
            col[28] = TableData.TableInfo.TABLE_MDATA_n_apdcl_billno;                   val[28] = Rcrypt.encode(imeino, apdcl_billno +"");
            col[29] = TableData.TableInfo.TABLE_MDATA_n_curr_reading;                   val[29] = Rcrypt.encode(imeino, "");

            DatabaseOperation DB = new DatabaseOperation(ctx);
            DB.updateInformation(DB, TableData.TableInfo.TABLE_MDATA_NAME, col, val, new String[]{TableData.TableInfo.TABLE_MDATA_id}, new String[]{conid});
        }
        new android.os.Handler().postDelayed(
                new Runnable() {
                    public void run() {
                        upload_start();
                    }
                }, 1000);
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

    private void setProgresBar(int d){
        PROGRESS_bar.setProgress(d);
        int sh = (int) cf.currency_round(((D_total * d)/100),0);
        PROGRESS_text.setText(d + "% ("+ sh +" / "+ D_total +") Please Wait...");

    }
}
