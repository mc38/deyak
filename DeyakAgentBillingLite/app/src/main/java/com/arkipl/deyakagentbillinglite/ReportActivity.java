package com.arkipl.deyakagentbillinglite;

import android.app.ProgressDialog;
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
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.google.config.Config;
import com.google.remote.RemoteAddress;
import com.google.remote.RemoteConnection;
import com.google.remote.ResponseAction;
import com.google.service.UploadService;

import java.util.ArrayList;

import in.deyak.supporting.CommonFunction;
import in.deyak.supporting.DatabaseOperation;
import in.deyak.supporting.TableData;


public class ReportActivity extends AppCompatActivity {

    Context ctx = this;
    Menu m;

    CommonFunction cf;
    DatabaseOperation dbo;

    LinearLayout PROGRESS_holder,UPLOAD_holder, DOWNLOAD_holder;
    TextView PROGRESS_text;
    ProgressBar PROGRESS_bar;
    ImageView UPLOAD_Button, DOWNLOAD_Button;
    TextView utv;

    int D_total = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_report);

        cf = new CommonFunction();
        dbo = new DatabaseOperation(ctx);

        utv = (TextView) this.findViewById(R.id.updatetotal);
        utv.setText(uremain()+"");
        ((LinearLayout) this.findViewById(R.id.uno_holder)).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                utv.setText(uremain()+"");
            }
        });
        D_total = uremain();

        all_action();
    }

    Cursor mCR;
    private int uremain(){
        mCR = dbo.selectsqlInformation(dbo,"select * from "+ TableData.TableInfo.TABLE_MDATA_NAME +" where "+ TableData.TableInfo.TABLE_MDATA_n_status +"<>''");
        if(mCR !=null) {
            return mCR.getCount();
        }else{
            return -1;
        }
    }


    private void all_action(){
        UploadService.notok = false;

        PROGRESS_holder = (LinearLayout) this.findViewById(R.id.progress_holder);
        PROGRESS_text = (TextView) this.findViewById(R.id.progress_text);
        PROGRESS_bar = (ProgressBar) this.findViewById(R.id.progress_bar);
        setProgresBar(0);

        UPLOAD_holder = (LinearLayout) this.findViewById(R.id.upload_button_holder);
        UPLOAD_Button = (ImageView) this.findViewById(R.id.upload_button);

        UPLOAD_Button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                upload_start();
            }
        });

        DOWNLOAD_holder = (LinearLayout) this.findViewById(R.id.download_holder);
        DOWNLOAD_Button = (ImageView) this.findViewById(R.id.down_button);
        DOWNLOAD_Button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                download_start();
            }
        });


    }


    private void upload_start(){
        utv.setText(uremain()+"");
        if(uremain() > 0) {
            cicon_show(false);

            DOWNLOAD_holder.setVisibility(View.GONE);
            UPLOAD_holder.setVisibility(View.GONE);
            PROGRESS_holder.setVisibility(View.VISIBLE);

            int pct = (int) Math.floor(((D_total - uremain())*100)/D_total);
            setProgresBar(pct);

            mCR.moveToFirst();
            upload_binarypic(mCR);

        }else if(uremain()<0){
            dbo.close();
            dbo = new DatabaseOperation(ctx);
            upload_start();
        }else{
            upload_reset("No data is remaining to upload");
        }
    }


    private void upload_reset(String msg){
        PROGRESS_holder.setVisibility(View.GONE);
        UPLOAD_holder.setVisibility(View.VISIBLE);
        DOWNLOAD_holder.setVisibility(View.VISIBLE);

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

    private void upload_binarypic(final Cursor cr){
        final String conid = cr.getString(0);

        final String c = cf.getCode(12);
        ArrayList<String> d = new ArrayList<String>();
        d.add(0, conid);
        d.add(1, cr.getString(2));
        d.add(2, cr.getString(50));

        String ra = RemoteAddress.AddressInfo.HOST + Config.getAccessURL(ctx) + RemoteAddress.AddressInfo.DATA_UPLOAD_BIN;
        RemoteConnection rconn = new RemoteConnection(ctx);
        rconn.authsenddata(ra, d, new ResponseAction() {
            @Override
            public void onSuccessAction(String r) {

                if(r.isEmpty()){
                    upload_reset("Server Problem");
                }else{
                    if(r.equals("1")) {
                        Log.d("File Upload Bulk","Binary image upload completed of consumer "+ cr.getString(1));
                        dataupload(cr);
                    }else{
                        upload_reset("Something fishy happening in the server.");
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
                                new android.os.Handler().postDelayed(
                                        new Runnable() {
                                            public void run() {
                                                upload_binarypic(cr);
                                            }
                                        }, tempconnwait);
                            }
                        })
                        .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                dialog.cancel();
                                dialog.dismiss();
                                upload_reset("Bulk data upload cancelled");
                            }
                        })
                        .setCancelable(false)
                        .show();
            }
        });
    }


    int tempconnno = 0, totaltempconnno = 40, tempconnwait = 1000;
    private void dataupload(final Cursor cr){
        tempconnwait = (int) Math.floor(RemoteAddress.AddressInfo.responseTimeOut + (cf.randomno(10,20) *1000));

        final String conid = cr.getString(0);

        final String c = cf.getCode(12);
        ArrayList<String> d = new ArrayList<String>();
        d.add(0, conid);
        d.add(1, cr.getString(2));

        d.add(2,  cr.getString(45));    //n_billno
        d.add(3,  cr.getString(46));    //n_status
        d.add(4,  cr.getString(47));    //n_reading_date
        d.add(5,  cr.getString(48));    //n_postmeter_read
        d.add(6,  cr.getString(49));    //n_meterpic
        d.add(7,  cr.getString(51));    //n_unit_consumed
        d.add(8,  cr.getString(52));    //n_unit_billed
        d.add(9,  cr.getString(53));    //n_consumption_day
        d.add(10, cr.getString(54));    //n_due_date
        d.add(11, cr.getString(55));    //n_energy_brkup
        d.add(12, cr.getString(56));    //n_energy_amount
        d.add(13, cr.getString(57));    //n_subsidy
        d.add(14, cr.getString(58));    //n_total_energy_charge
        d.add(15, cr.getString(59));    //n_fixed_charge
        d.add(16, cr.getString(60));    //n_electricity_duty
        d.add(17, cr.getString(61));    //n_fppa_charge
        d.add(18, cr.getString(62));    //n_current_demand
        d.add(19, cr.getString(63));    //n_total_arrear
        d.add(20, cr.getString(64));    //n_net_bill_amount
        d.add(21, cr.getString(65));    //n_net_bill_amount_after_duedate
        d.add(22, cr.getString(66));    //n_gps_verfication
        d.add(23, cr.getString(67));    //n_ocr_analysis
        d.add(24, cr.getString(68));    //n_pf
        d.add(25, cr.getString(69));    //n_current_surcharge
        d.add(26, cr.getString(70));    //n_meter_rent
        d.add(27, cr.getString(71));    //n_unit_pf
        d.add(28, cr.getString(72));    //n_apdcl_billno

        d.add(29, cr.getString(73));    //n_blnk_4
        d.add(30, cr.getString(74));    //n_blnk_5
        d.add(31, cr.getString(75));    //n_blnk_6
        d.add(32, cr.getString(76));    //n_blnk_7
        d.add(33, cr.getString(77));    //n_blnk_8
        d.add(34, cr.getString(78));    //n_blnk_9

        d.add(35, cr.getString(79));    //n_survey_gps_lati
        d.add(36, cr.getString(80));    //n_survey_gps_longi
        d.add(37, cr.getString(81));    //n_survey_gps_alti
        d.add(38, cr.getString(82));    //n_survey_meterheight
        d.add(39, cr.getString(83));    //n_survey_mobno
        d.add(40, cr.getString(84));    //n_survey_meterslno
        d.add(41, cr.getString(85));    //n_survey_metertype
        d.add(42, cr.getString(86));    //n_survey_consumertype
        d.add(43, cr.getString(87));    //n_survey_nwsignal

        String ra = RemoteAddress.AddressInfo.HOST + Config.getAccessURL(ctx) + RemoteAddress.AddressInfo.DATA_UPDATE_LINK;
        RemoteConnection rconn = new RemoteConnection(ctx);
        rconn.authsenddata(ra, d, new ResponseAction() {
            @Override
            public void onSuccessAction(String r) {

                if(r.isEmpty()){
                    upload_reset("Server Problem");
                }else{
                    if(r.equals("1")) {

                        dbo.deleteInformation(dbo, TableData.TableInfo.TABLE_MDATA_NAME,new String[]{TableData.TableInfo.TABLE_MDATA_id},new String[]{conid});

                        Log.d("File Upload Bulk","Data uploading completed of consumer "+ cr.getString(1));
                        if(tempconnno < totaltempconnno) {
                            upload_start();
                            tempconnno++;
                        }else {
                            tempconnno = 0;
                            new android.os.Handler().postDelayed(
                                    new Runnable() {
                                        public void run() {
                                            upload_start();
                                        }
                                    }, tempconnwait);
                        }

                    }else{
                        upload_reset("Something fishy happening in the server.");
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
                                                dataupload(cr);
                                            }
                                        }, tempconnwait);
                            }
                        })
                        .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                dialog.cancel();
                                dialog.dismiss();
                                upload_reset("Bulk data upload cancelled");
                            }
                        })
                        .setCancelable(false)
                        .show();
            }
        });
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    ProgressDialog ploading;
    boolean down_complete = true;
    private void download_start(){
        ploading = new ProgressDialog(ctx);
        ploading.setMessage("Please wait... data downloading");
        ploading.setCancelable(false);
        ploading.show();

        data_download();

    }


    private void data_download(){
        down_complete = false;
        final String c = cf.getCode(12);

        ArrayList<String> d = new ArrayList<String>();

        String ra = RemoteAddress.AddressInfo.HOST + Config.getAccessURL(ctx) + RemoteAddress.AddressInfo.DATA_DOWN_LINK;
        RemoteConnection rconn = new RemoteConnection(ctx);
        rconn.authsenddata(ra, d, new ResponseAction() {
            @Override
            public void onSuccessAction(String r) {

                Log.d("File Upload Bulk","Data downloading completed. "+ r);
                if(!(r.equals("1"))){
                    ArrayList<String> q = cf.json_decode(cf.base64_decode(r));

                    String[] col = new String[38];                                      String[] val = new String[38];
                    col[0]  = TableData.TableInfo.TABLE_MDATA_id;                       val[0]  = q.get(0).toString();
                    col[1]  = TableData.TableInfo.TABLE_MDATA_bid;                      val[1]  = q.get(1).toString();
                    col[2]  = TableData.TableInfo.TABLE_MDATA_mydate;                   val[2]  = q.get(2).toString();
                    col[3]  = TableData.TableInfo.TABLE_MDATA_equation_category;        val[3]  = q.get(3).toString();
                    col[4]  = TableData.TableInfo.TABLE_MDATA_ocr;                      val[4]  = q.get(4).toString();
                    col[5]  = TableData.TableInfo.TABLE_MDATA_survey;                   val[5]  = q.get(5).toString();
                    col[6]  = TableData.TableInfo.TABLE_MDATA_subdivision;              val[6]  = q.get(6).toString();
                    col[7]  = TableData.TableInfo.TABLE_MDATA_dtrno;                    val[7]  = q.get(7).toString();
                    col[8]  = TableData.TableInfo.TABLE_MDATA_cid;                      val[8]  = q.get(8).toString();
                    col[9]  = TableData.TableInfo.TABLE_MDATA_oldcid;                   val[9]  = q.get(9).toString();
                    col[10] = TableData.TableInfo.TABLE_MDATA_qrcode;                   val[10] = q.get(10).toString();
                    col[11] = TableData.TableInfo.TABLE_MDATA_gps_lati;                 val[11] = q.get(11).toString();
                    col[12] = TableData.TableInfo.TABLE_MDATA_gps_longi;                val[12] = q.get(12).toString();
                    col[13] = TableData.TableInfo.TABLE_MDATA_gps_alti;                 val[13] = q.get(13).toString();
                    col[14] = TableData.TableInfo.TABLE_MDATA_consumer_name;            val[14] = q.get(14).toString();
                    col[15] = TableData.TableInfo.TABLE_MDATA_consumer_address;         val[15] = q.get(15).toString();
                    col[16] = TableData.TableInfo.TABLE_MDATA_consumer_category;        val[16] = q.get(16).toString();
                    col[17] = TableData.TableInfo.TABLE_MDATA_connection_type;          val[17] = q.get(17).toString();
                    col[18] = TableData.TableInfo.TABLE_MDATA_mfactor;                  val[18] = q.get(18).toString();
                    col[19] = TableData.TableInfo.TABLE_MDATA_connection_load;          val[19] = q.get(19).toString();
                    col[20] = TableData.TableInfo.TABLE_MDATA_meter_no;                 val[20] = q.get(20).toString();
                    col[21] = TableData.TableInfo.TABLE_MDATA_reserve_unit;             val[21] = q.get(21).toString();
                    col[22] = TableData.TableInfo.TABLE_MDATA_premeter_read_date;       val[22] = q.get(22).toString();
                    col[23] = TableData.TableInfo.TABLE_MDATA_premeter_read;            val[23] = q.get(23).toString();
                    col[24] = TableData.TableInfo.TABLE_MDATA_slab;                     val[24] = q.get(24).toString();
                    col[25] = TableData.TableInfo.TABLE_MDATA_meter_rent;               val[25] = q.get(25).toString();
                    col[26] = TableData.TableInfo.TABLE_MDATA_principal_arrear;         val[26] = q.get(26).toString();
                    col[27] = TableData.TableInfo.TABLE_MDATA_arrear_surcharge;         val[27] = q.get(27).toString();
                    col[28] = TableData.TableInfo.TABLE_MDATA_current_surcharge;        val[28] = q.get(28).toString();
                    col[29] = TableData.TableInfo.TABLE_MDATA_adjustment;               val[29] = q.get(29).toString();
                    col[30] = TableData.TableInfo.TABLE_MDATA_rate_eduty;               val[30] = q.get(30).toString();
                    col[31] = TableData.TableInfo.TABLE_MDATA_rate_surcharge;           val[31] = q.get(31).toString();
                    col[32] = TableData.TableInfo.TABLE_MDATA_rate_fppa;                val[32] = q.get(32).toString();
                    col[33] = TableData.TableInfo.TABLE_MDATA_multibill;                val[33] = q.get(33).toString();
                    col[34] = TableData.TableInfo.TABLE_MDATA_prevbillduedate;          val[34] = q.get(34).toString();
                    col[35] = TableData.TableInfo.TABLE_MDATA_premeterstatus;           val[35] = q.get(35).toString();
                    col[36] = TableData.TableInfo.TABLE_MDATA_cs_pa;                    val[36] = q.get(36).toString();
                    col[37] = TableData.TableInfo.TABLE_MDATA_search_reject;            val[37] = q.get(37).toString();

                    Cursor chcr = dbo.selectsqlInformation(dbo,"select "+ TableData.TableInfo.TABLE_MDATA_id +" from "+ TableData.TableInfo.TABLE_MDATA_NAME +" where "+ TableData.TableInfo.TABLE_MDATA_id +"='"+ q.get(0).toString() +"'");
                    if(chcr.getCount() >0){
                        dbo.deleteInformation(dbo, TableData.TableInfo.TABLE_MDATA_NAME,new String[]{TableData.TableInfo.TABLE_MDATA_id},new String[]{q.get(0).toString()});
                    }

                    dbo.insertInformation(dbo, TableData.TableInfo.TABLE_MDATA_NAME, col, val);
                    Log.d("File Upload Bulk","Data inserted to database. "+ r);

                    data_download();

                }else {
                    ploading.hide();
                    ploading.cancel();
                }

            }

            @Override
            public void onFailureAction(int code) {
                ploading.hide();
                ploading.cancel();
                cf.makeToast(ctx,"Intenet Connection problem");
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
            Intent home = new Intent(ctx, HomeActivity.class);
            this.finish();
            startActivity(home);
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
        int rem = D_total - uremain();
        PROGRESS_text.setText(d + "% ("+ rem +" / "+ D_total +") Please Wait... Uploading");

    }
}
