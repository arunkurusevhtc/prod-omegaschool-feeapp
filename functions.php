<?php

/****************FUNCTIONS - START********************/

/********Common Functions - Start*********/
/*************Change Status - Start****************/
CREATE OR REPLACE FUNCTION statusupdate(param character varying, stat status,uid integer,id integer) 
RETURNS integer AS $$
BEGIN
      EXECUTE 'UPDATE ' || quote_ident(param) || ' SET status=$1, "updatedOn"=CURRENT_TIMESTAMP, "updatedBy"=$2 WHERE id = $3' USING stat,uid,id;
      return 1;
END;
$$ LANGUAGE plpgsql; 
/*************Change Status - Start****************/

/*************Delete Function - Start****************/
CREATE OR REPLACE FUNCTION deleteupdate(param character varying,uid integer,id integer) 
RETURNS integer AS $$
BEGIN
      EXECUTE 'UPDATE ' || quote_ident(param) || ' SET deleted=1, "updatedOn"=CURRENT_TIMESTAMP, "updatedBy"=$1 WHERE id = $2' USING uid,id;
      return 1;
END;
$$ LANGUAGE plpgsql; 
/*************Delete Function - Start****************/
/********Common Functions - End*********/

/******** Registration Function - Start*********/
  CREATE OR REPLACE FUNCTION reg_val(fname character(20),lname character(20),un character(30),ep character(50),es character(50),pn1 bigint,pn2 bigint,pw character(150),vc character(15))
    RETURNS int AS $$
  DECLARE exist int;
  BEGIN

      PERFORM  1 FROM public.tbl_parents WHERE email = ep;      

        IF NOT found THEN
           INSERT INTO public.tbl_parents("firstName", "lastName", "userName", "email","secondaryEmail" , "mobileNumber","secondaryNumber", "password", "verifyCode", "createdOn") VALUES(fname,lname,un,ep,es,pn1,pn2,pw,vc,CURRENT_TIMESTAMP);
           exist = 0;
         ELSE 
           exist = 1;
        END IF;  
        return exist; 
  END;

   $$ LANGUAGE plpgsql
 /******** Registration Function - End *********/

 /********* Verify Code Function - Start********/
 CREATE OR REPLACE FUNCTION verifyCode(code character(100)) 
  RETURNS int AS $$
DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "verifyCode" = code ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_parents SET "verifyCode" = 0 WHERE "verifyCode" = code;
        cnt = 1;
    END IF;  
    return cnt;
END;
    $$ LANGUAGE plpgsql
/********* Verify Code Function - End********/

/*******Function for changepass - Start*****/
CREATE OR REPLACE FUNCTION changepass(mail character(50), newpass character(150)) 
  RETURNS int AS $$
DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "email" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_parents SET "password" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "email" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
    $$ LANGUAGE plpgsql
/*******Function for changepass - End*****/

/*******Function for changeteacherpassword - Start*****/
CREATE OR REPLACE FUNCTION changeteacherpassword(mail character(50), newpass character(150)) 
  RETURNS int AS $$
DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_teachers WHERE "email" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_teachers SET "password" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "email" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
    $$ LANGUAGE plpgsql
/*******Function for changeteacherpassword - End*****/

/*******Function for changeadminpassword - Start*****/
CREATE OR REPLACE FUNCTION changeadminpassword(mail character(50), newpass character(150)) 
  RETURNS int AS $$
DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_admin WHERE "adminEmail" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_admin SET "adminPassword" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "adminEmail" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
    $$ LANGUAGE plpgsql
/*******Function for changeadminpassword - End*****/

/*******Function for changeparentpassword - Start*****/
CREATE OR REPLACE FUNCTION changeparentpassword(mail character(50), newpass character(150)) 
  RETURNS int AS $$
DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "email" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_parents SET "password" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "email" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
    $$ LANGUAGE plpgsql
/*******Function for changeparentpassword - End*****/

/****Add Student function start*******/
CREATE OR REPLACE FUNCTION add_stud(pid integer,mob character(30),sid character(10))
  RETURNS int AS $$
  DECLARE fid int;
BEGIN
  PERFORM  1 FROM public.tbl_student WHERE ("parentId" IS NULL || "parentId" = '0') AND "studentId"=sid ;      

    IF found THEN
     UPDATE public.tbl_student SET "parentId"=pid ,  "mobileNumber" = mob , "updatedOn"=CURRENT_TIMESTAMP WHERE "studentId" = sid RETURNING id INTO fid ;        
  ELSE 
    fid = 0;
  END IF;
  return fid; 
END;

 $$ LANGUAGE plpgsql;
 /*******Add Student function end******/

/********Functions for Admin Page- Start*******/
/*******Function for editadm - Start*****/
CREATE OR REPLACE FUNCTION editAdm(did integer, admn character(20),admm character(30), admp character(20), uid integer) 
  RETURNS int AS $$
BEGIN

    PERFORM  1 FROM public.tbl_admin WHERE "adminEmail" = admm AND "id" != did AND "deleted" = '0';      

    IF NOT found THEN
    
        UPDATE tbl_admin SET "adminName" = admn,"adminEmail" = admm,"adminPassword" = admp,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF; 
END;
    $$ LANGUAGE plpgsql
/*******Function for editadm - End*****/

/*******Function for Add Admin - Start*****/
CREATE OR REPLACE FUNCTION addadm(did integer,admn character(30), adme character(30), admp character(20)) 
  RETURNS int AS $$
BEGIN
    
    PERFORM  1 FROM public.tbl_admin WHERE "adminEmail" = adme AND "deleted" = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_admin("adminEmail", "adminPassword", "adminName", "createdOn", "createdBy")
       VALUES (adme,admp,admn,CURRENT_TIMESTAMP,did);
         
    return 1;
    ELSE 
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Admin - End*****/
/********Functions for Admin Page- End*******/

/********Functions for Parent Page- Start*******/
/*******Function for editpar - Start*****/
CREATE OR REPLACE FUNCTION editpar(did integer, fn character(20),ln character(20),un character(40), ep character(50), es character(50),p character(20),pn bigint,mn bigint,uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "email" = ep AND "id" != did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_parents SET "firstName" = fn,"lastName" = ln,"userName" = un,"email" = ep,"secondaryEmail"=es,"password" = p,"mobileNumber" = pn,"secondaryNumber" = mn,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE 
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editpar - End*****/

/*******Function for Add Parent - Start*****/
CREATE OR REPLACE FUNCTION addpar(fn character(20),ln character(20),un character(40), ep character(50),es character(50),p character(20),pn bigint,mn bigint, uid integer) 
  RETURNS int AS $$
BEGIN

    PERFORM  1 FROM public.tbl_parents WHERE "email" = ep AND "deleted" = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_parents("firstName", "lastName", "userName","email", "secondaryEmail","password","mobileNumber","secondaryNumber","createdOn","createdBy") VALUES (fn,ln,un,ep,es,p,pn,mn,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Parent - End*****/
/********Functions for Parent Page- End*******/


/********Functions for Student Page- Start*******/
/*******Function for editstd - Start*****/
CREATE OR REPLACE FUNCTION editstd(sid character(10),sn character(20),s character(15),c character(20),sec character(40), t character(30),pi integer,mail character(150),mnum character(30),transtg integer,transneed character(10),hostel character(1),lunch character(10),acayear character(15),uid integer,did integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_student WHERE "studentId" = sid AND "id" != did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_student SET "studentId" = sid,"studentName" = sn,"stream" = s,"class" = c,"section" = sec,"term" = t,"parentId" = pi,"email" = mail, "mobileNumber" = mnum, "transport_stg" = transtg, "transport_need" = transneed, "hostel_need" = hostel, "lunch_need" = lunch, "academic_yr" = acayear, "updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editstd - End*****/

/*******Function for Add Student - Start*****/
CREATE OR REPLACE FUNCTION addstd(sid character(10),sn character(20),s character(15),c character(20),sec character(40), t character(30),pi integer,mail character(150),mnum character(30),uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_student WHERE "studentId" = sid AND "deleted" = '0';     

    IF NOT found THEN
       INSERT INTO public.tbl_student("studentId", "studentName", "stream", "class", "section","term", "parentId","email", "mobileNumber", "createdOn","createdBy")
       VALUES (sid,sn,s,c,sec,t,pi,mail,mnum,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Student - End*****/
/********Functions for Student Page- End*******/

/********Functions for Class Page- Start*******/
/*******Function for editclass- Start*****/
CREATE OR REPLACE FUNCTION editclass(did integer, cn character(20),des character(60),stid integer,uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_class WHERE "class_list" = cn AND "streamId" = stid AND "id"!= did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_class SET "class_list"=cn,"description"=des,"streamId" = stid,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editclass - End*****/

/*******Function for Add Class - Start*****/
CREATE OR REPLACE FUNCTION addclass(cn character(20),des character(60),stid integer,uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_class WHERE "class_list" = cn AND "streamId" = stid AND "deleted" = '0';      
   IF NOT found THEN

       INSERT INTO public.tbl_class("class_list", "description","streamId","createdOn","createdBy")
       VALUES (cn,des,stid,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Class - End*****/
/********Functions for Class Page- End*******/

/********Functions for Class Page- Start*******/
/*******Function for editclass- Start*****/
CREATE OR REPLACE FUNCTION editfeegroup(did integer, fn character(20),des character(20),uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_group WHERE "feeGroup" =fn AND "id"!=did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_fee_group SET "feeGroup"=fn,"description"=des,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editclass - End*****/

/*******Function for Add Class - Start*****/
CREATE OR REPLACE FUNCTION addfeegroup(fn character(20),des character(100),uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_group WHERE "feeGroup" =fn AND "deleted" = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_fee_group("feeGroup", "description","createdOn","createdBy")
       VALUES (fn,des,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Class - End*****/
/********Functions for Class Page- End*******/


/********Functions for Stream Page- Start*******/
/*******Function for editStream- Start*****/
CREATE OR REPLACE FUNCTION editstream(did integer, s character(20),des character(20),uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_stream WHERE "stream" = s AND "id"! = did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_stream SET "stream" = s,"description" = des,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editstream - End*****/

/*******Function for Add Stream - Start*****/
CREATE OR REPLACE FUNCTION addstream(s character(20),des character(100),uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_stream WHERE "stream" = s AND "deleted" = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_stream("stream", "description","createdOn","createdBy")
  VALUES (s,des,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Stream - End*****/
/********Functions for Stream Page- End*******/

/********Functions for Stream Page- Start*******/
/*******Function for editStream- Start*****/
CREATE OR REPLACE FUNCTION editlatefee(did integer, n integer,amt real, uid integer) 
  RETURNS int AS $$
BEGIN
    

    PERFORM  1 FROM public.tbl_late_fee WHERE "noOfDays" = n AND "id" != did;      

    IF NOT found THEN
    UPDATE tbl_late_fee SET "noOfDays" = n,"amount" = amt,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editStream - End*****/

/*******Function for Add Stream - Start*****/
CREATE OR REPLACE FUNCTION addlatefee(n integer,amt real,uid integer) 
  RETURNS int AS $$
BEGIN
    
       
    PERFORM  1 FROM public.tbl_late_fee WHERE "noOfDays" = n ;      

    IF NOT found THEN
    INSERT INTO public.tbl_late_fee("noOfDays", "amount","createdOn","createdBy")
    VALUES (n,amt,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add stream - End*****/
/********Functions for stream Page- End*******/

/********Functions for Student Page- Start*******/
/*******Function for editstd - Start*****/
CREATE OR REPLACE FUNCTION edittax(did integer, ed date,tt character(20), ct real,st real,uid integer) 
  RETURNS int AS $$
BEGIN
    
       
PERFORM  1 FROM public.tbl_tax WHERE "taxType" = tt AND "id"! = did AND "deleted" = '0';      

    IF NOT found THEN
         UPDATE tbl_tax SET "effectiveDate" = ed,"taxType" = tt,"centralTax" = ct,"stateTax" = st,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editstd - End*****/

/*******Function for Add Student - Start*****/
CREATE OR REPLACE FUNCTION addtax(ed date,tt character(20),ct real, st real,uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_tax WHERE "taxType" = tt AND "deleted" = '0';      

    IF NOT found THEN
    INSERT INTO public.tbl_tax("effectiveDate", "taxType", "centralTax","stateTax","createdOn","createdBy")
    VALUES (ed,tt,ct,st,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Student - End*****/
/********Functions for Student Page- End*******/


/********Functions for Fee Type Page- Start*******/
/*******Function for editfeetype- Start*****/
CREATE OR REPLACE FUNCTION editfeetype(did integer, ft character(20),des character(20),uid integer,tt character(100),grp character(40),mand integer, app character(10)) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_type WHERE "feeType" =ft AND "id"!=did;      

    IF NOT found THEN
        UPDATE tbl_fee_type SET "feeType"=ft,"description"=des,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid, "tax"=tt,"feeGroup"=grp, "mandatory"=mand,"applicable" = app  WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editfeetype - End*****/

/*******Function for Add fee type - Start*****/
CREATE OR REPLACE FUNCTION addfeetype(ft character(20),des character(100),uid integer,tt character(100),grp character(40),mand integer, app character(10)) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_type WHERE "feeType" =ft ;      

    IF NOT found THEN
       INSERT INTO public.tbl_fee_type("feeType", "description","createdOn","createdBy","tax","feeGroup","mandatory","applicable")
       VALUES (ft,des,CURRENT_TIMESTAMP,uid,tt,grp,mand,app);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add feetype - End*****/
/********Functions for Fee Type Page- End*******/

/********Functions for Fee Type Page- Start*******/
/*******Function for editfeetype- Start*****/
CREATE OR REPLACE FUNCTION edityear(did integer,yr character,uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_academic_year WHERE "year" =yr AND "id"!=did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_academic_year SET "year"=yr,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editfeetype - End*****/

/*******Function for Add fee type - Start*****/
CREATE OR REPLACE FUNCTION addyear(yr character,uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_academic_year WHERE tbl_academic_year."year" = yr AND "deleted" = '0' ;      

    IF NOT found THEN
       INSERT INTO public.tbl_academic_year("year","createdOn","createdBy")
       VALUES (yr,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add feetype - End*****/
/********Functions for Fee Type Page- End*******/


/**************Teacher Table - Start**********/
/*********Add Teacher - Start**************/


CREATE OR REPLACE FUNCTION addTeacher(name character(50),e character(50),pass character(150),clas character(10),pn1 bigint,pn2 bigint,createdBy character(50),st character(10))
  RETURNS int AS $$
DECLARE exist int;
BEGIN

    PERFORM  1 FROM public.tbl_teachers WHERE email = e AND "deleted" = '0';      

      IF NOT found THEN
      INSERT INTO public.tbl_teachers(name, email, password, class,"phoneNumber", "mobileNumber","createdBy", "createdOn",stream) VALUES (name, e, pass,clas,pn1,pn2,createdby,CURRENT_TIMESTAMP,st);         
         exist = 0;
       ELSE 
         exist = 1;
      END IF;  
      return exist; 
END;

 $$ LANGUAGE plpgsql;
/*********Add Teacher - End**************/
/*********Edit Teacher - Start**************/
 CREATE OR REPLACE FUNCTION editTeacher(tid integer,tname character(50), clas character(10),e character(50),pn1 bigint,pn2 bigint,pass character(150),updatedBy character(50),st character(10))
  RETURNS int AS $$
DECLARE exist int;
BEGIN

    PERFORM  1 FROM public.tbl_teachers WHERE email = e AND id != tid AND "deleted" = '0';      

      IF NOT found THEN
        UPDATE tbl_teachers SET "name" = tname,"class" = clas,"email" = e,"phoneNumber" = pn1,"mobileNumber" = pn2,"password" = pass,"updatedBy" = updatedBy,"updatedOn" = CURRENT_TIMESTAMP,"stream" = st WHERE id = tid;     
         exist = 0;
       ELSE 
         exist = 1;
      END IF;  
      return exist; 
END;

 $$ LANGUAGE plpgsql;
/*********Edit Teacher - End**************/
/**************Teacher Table - End**********/

/********Functions for Comments Page- Start*******/
/*******Function for editcomments- Start*****/
CREATE OR REPLACE FUNCTION editcomments(did integer, pn character(20),com text,sdate date,edate date,uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_comments WHERE "pageName" = pn AND "id"! = did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_comments SET "pageName" = pn,"comments" = com,"startdate" = sdate,"enddate" = edate,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editcomments - End*****/


/*******Function for Add Comments - Start*****/
CREATE OR REPLACE FUNCTION addcomments(pn character(20),com text,sdate date,enddate date, uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_comments WHERE "pageName" = pn AND "deleted" = '0' ;      

    IF NOT found THEN
       INSERT INTO public.tbl_comments("pageName","comments","startdate","enddate","createdOn","createdBy")
       VALUES (pn,com,sdate,enddate,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Comments - End*****/
/********Functions for Comments Page- End*******/

 /*********Functions For Challan -Start *******/
 /*****Create Temp Challan - Start******/
CREATE OR REPLACE FUNCTION createTempChallan(cn character(50),studId character(30),createdBy integer,str integer,classlist character(5),trm character(5),name character(50),yr character(20))
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_challans WHERE "studentId" =studId AND "classList" = classlist  AND "term" = trm  AND "stream" = str;
    IF NOT found THEN
      INSERT INTO public.tbl_temp_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","studentName","academicYear") VALUES (cn, studId,'Prov.Promoted',createdBy,CURRENT_TIMESTAMP,str,classlist,trm,name,yr) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
 $$ LANGUAGE plpgsql;
 /*****Create Temp Challan - End******/
  /*****Update Temp Challan - Start******/
CREATE OR REPLACE FUNCTION updatetempchallan(cn character(50),studId character(30),classlist character(5),feetypes character(100),term character(5),name character(50),studStatus character(15),createdBy integer,tot real,str integer,remark character(256),due date,feegrp character(40),yr character(20))
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_temp_challans WHERE "challanNo" = cn;
    IF found THEN
        INSERT INTO public.tbl_temp_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","studentName","feeTypes","total","remarks","duedate", "feeGroup","academicYear") VALUES (cn, studId,studStatus,createdBy,CURRENT_TIMESTAMP,str,classlist,term,name,feetypes,tot,remark,due,feegrp,yr); 
      cid = 1; 
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
 $$ LANGUAGE plpgsql;
 /*****Update Temp Challan - End******/
 
 /*****Update Temp Challan - Start******/
 CREATE OR REPLACE FUNCTION createlatefeechallan(cn character(50),studId character(30),classlist character(5),trm character(5),latefee real,str integer,due date,yr character(20), acayear integer, uid integer, clid integer)
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_challans WHERE "studentId" =studId AND "classList" = classlist  AND "term" = trm  AND "stream" = str;
    IF found THEN
       PERFORM 1 FROM public.tbl_challans WHERE "challanNo"= cn AND "feeGroup" ='LATE FEE' AND "challanStatus" = 0;
          IF found THEN
            DELETE FROM tbl_challans WHERE "challanNo"= cn AND "feeGroup" ='LATE FEE' AND "challanStatus" = 0;

            DELETE FROM tbl_demand WHERE "challanNo"= cn AND "feeGroup" = '0' AND "challanStatus" = 0;

            INSERT INTO public.tbl_challans("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "total") VALUES (cn,studId,classlist,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,'LATE FEE',yr, uid,latefee) RETURNING id INTO cid;

            INSERT INTO public.tbl_demand("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "feeType", "total") VALUES (cn,studId,clid,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,0,acayear, uid, 0, latefee) RETURNING id INTO cid;

          ELSE       
            INSERT INTO public.tbl_challans("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "total") VALUES (cn,studId,classlist,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,'LATE FEE',yr, uid,latefee) RETURNING id INTO cid; 

            INSERT INTO public.tbl_demand("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "feeType", "total") VALUES (cn,studId,clid,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,0,acayear, uid, 0, latefee) RETURNING id INTO cid; 

          END IF;
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
 $$ LANGUAGE plpgsql;
/*****Update Temp Challan - End******/

 /*****Create Challan - Start******/

 CREATE OR REPLACE FUNCTION createChallan(cid integer)
  RETURNS int AS $$     
BEGIN

    INSERT INTO tbl_challans("challanNo", "studentId", "feeTypes", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","org_total","stream","remarks","duedate","feeGroup","academicYear")  SELECT "challanNo", "studentId", "feeTypes", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","total","stream"::int,"remarks","duedate","feeGroup","academicYear"  FROM tbl_temp_challans WHERE id=cid;
    return 1;
END;
 $$ LANGUAGE plpgsql; 
 /*****Create Challan - End******/
   /*****Update Temp Challan - Start******/
CREATE OR REPLACE FUNCTION editcreatedchallans(cn character(50),studId character(30),classlist character(5),feetypes character(100),term character(5),studStatus character(15),createdBy integer,tot real,str integer,remark character(256),due date,feegrp character(40),wavep integer,wavea real,wavet real,orgtotal real,yr character(20))
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
        INSERT INTO public.tbl_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","feeTypes","total","remarks","duedate", "feeGroup","waivedPercentage","waivedAmount","waivedTotal","org_total","academicYear") VALUES (cn, studId,studStatus,createdBy,CURRENT_TIMESTAMP,str,classlist,term,feetypes,tot,remark,due,feegrp,wavep,wavea,wavet,orgtotal,yr); 
      cid = 1; 
    return cid;
END;
 $$ LANGUAGE plpgsql;
 /*****Update Temp Challan - End******/
  /*********Functions For Challan - End *******/

/*******Functions for Feeconfiguration - Start********/
/*******Functions for Editfeeconfiguration - Start********/
CREATE OR REPLACE FUNCTION editfeeconfiguration(yr character(10), str character(20),sem character(5),feetype character(30),updatedby integer,amt real,clas integer, fid integer)
  RETURNS int AS $$
  DECLARE exist int;
BEGIN
      PERFORM  1 FROM public.tbl_fee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND id!=fid; 

 
IF NOT FOUND THEN
            UPDATE public.tbl_fee_configuration SET "academicYear"= yr,stream=str,semester=sem,"feeType"=feetype,"updatedBy"=updatedby,"updatedOn"=CURRENT_TIMESTAMP,amount=amt,"class"=clas WHERE id = fid;           
          exist = 1;
       ELSE 
         exist = 0;
      END IF;  
    return exist; 
END;
    $$ LANGUAGE plpgsql 
/*******Functions for Editfeeconfiguration - End********/
/*******Functions for Addfeeconfiguration - Start********/
CREATE OR REPLACE FUNCTION addfeeconfiguration(yr character(10), str character(20),sem character(5),feetype character(30),createdby integer,amt real,clas integer)
  RETURNS int AS $$
  DECLARE exist int;
BEGIN
      PERFORM  1 FROM public.tbl_fee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0' AND amount = amt ;

        IF NOT FOUND THEN
            PERFORM  1 FROM public.tbl_fee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0'
            IF NOT FOUND THEN 
                INSERT INTO public.tbl_fee_configuration("academicYear","stream","semester","feeType","createdBy","createdOn","amount","class") VALUES (yr,str,sem,feetype,createdby,CURRENT_TIMESTAMP,amt,clas);
            ELSE
                UPDATE public.tbl_fee_configuration SET amount = amt WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0';
            END IF;
          exist = 1;  
        ELSE 
            exist = 0;
        END IF;  
    return exist; 
END;
    $$ LANGUAGE plpgsql
/*******Functions for AddFeeconfiguration - End********/
/*******Functions for Feeconfiguration - End********/

/*******Functions for fee_entry - Start********/
CREATE OR REPLACE FUNCTION fee_entry(sid character(8),sname character(20),ayr character(20),stream character(10),clas character(10),sem character(5),ftypes character(50),total numeric(10),cby integer,chlno character(40))
  RETURNS int AS $$
  DECLARE fid int;
BEGIN
     INSERT INTO tbl_variable_fee_entry ("studentId","studentName","academicYear","stream","class","semester","feeType","total","createdBy","createdOn","challanNo") VALUES (sid,sname,ayr,stream,clas,sem,ftypes,total,cby,CURRENT_TIMESTAMP,chlno) RETURNING id INTO fid ; 
     return fid;     
END;

 $$ LANGUAGE plpgsql;
/*******Functions for fee_entry - End********/

/*******Functions for fee_entry_update - Start********/
CREATE OR REPLACE FUNCTION fee_entry_update(transid character(8),fid character(20),uby integer)
  RETURNS int AS $$
BEGIN
     UPDATE tbl_variable_fee_entry SET "transId" = transid, "updatedBy" = uby, "updatedOn" = CURRENT_TIMESTAMP WHERE id = fid;
     return 1;    
END;

 $$ LANGUAGE plpgsql;


// CREATE OR REPLACE FUNCTION cheque_fee_entry_update(uby integer, ptype character(30),bnk character(100), cheque_no character(100),pdate date,challanno character(50),grp character(40))
//   RETURNS int AS $$
// BEGIN
//      UPDATE tbl_variable_fee_entry SET "transId" = 0, "updatedBy" = uby, "updatedOn" = CURRENT_TIMESTAMP, "pay_type" = ptype, "bank" = bnk, "cheque_dd_no" = cheque_no, "paid_date" = pdate WHERE "challanNo" = chlno AND "feeGroup" = grp;
//      UPDATE tbl_challans SET "challanStatus" = 1,"updatedBy" = uby, "updatedOn" = CURRENT_TIMESTAMP, "pay_type" = ptype, "bank" = bnk, "cheque_dd_no" = cheque_no, "paid_date" = pdate WHERE "challanNo" = chlno AND "feeGroup" = grp;
//      return 1;    
// END;

//  $$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION new_cheque_fee_entry(sid character(8),sname character(20),stream character(10),clas character(10),sem character(5),ftypes character(50),total numeric(10),chlno character(40),ptype character(30),bnk character(100), cheque_no character(100),pdate date,grp character(40),academic character(20),uby integer,remark character(256),dateupdate date)
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_variable_fee_entry WHERE "challanNo" = chlno AND "feeGroup" = grp AND "academicYear" = academic AND "semester" = sem;
    IF NOT FOUND THEN

    INSERT INTO tbl_variable_fee_entry("studentId","studentName","stream","class","semester","feeType","total","createdBy","createdOn","challanNo","pay_type","bank","cheque_dd_no","paid_date","academicYear","feeGroup","transId") VALUES (sid,sname,stream,clas,sem,ftypes,total,uby,CURRENT_TIMESTAMP,chlno,ptype,bnk,cheque_no,pdate,grp,academic,0);

     UPDATE tbl_challans SET "challanStatus" = 1,"updatedBy" = uby, "updatedOn" = dateupdate, "pay_type" = ptype, "bank" = bnk, "cheque_dd_no" = cheque_no, "paid_date" = pdate, "chequeRemarks" = remark WHERE "challanNo" = chlno AND "feeGroup" = grp;
     return 1; 
     ELSE   

     UPDATE tbl_variable_fee_entry SET "transId" = 0, "updatedBy" = uby, "updatedOn" = CURRENT_TIMESTAMP, "pay_type" = ptype, "bank" = bnk, "cheque_dd_no" = cheque_no, "paid_date" = pdate WHERE "challanNo" = chlno AND "feeGroup" = grp;

     UPDATE tbl_challans SET "challanStatus" = 1,"updatedBy" = uby, "updatedOn" = dateupdate, "pay_type" = ptype, "bank" = bnk, "cheque_dd_no" = cheque_no, "paid_date" = pdate, "chequeRemarks" = remark WHERE "challanNo" = chlno AND "feeGroup" = grp;
     return 0;    
     END IF;
END;

 $$ LANGUAGE plpgsql;
/*******Functions for fee_entry_update - End********/

/*******Functions for paymentEntry - Start********/
CREATE OR REPLACE FUNCTION paymentEntry(pid integer,sid integer,amount real,tstatus character(20),tnum character(20),returnCode text,remarks character(30),tid character(20),tdate date,cby integer)
  RETURNS int AS $$
  DECLARE payid int;
BEGIN
     INSERT INTO tbl_payments ("parentId","studentId","amount","transStatus","transNum","returnCode","remarks","transId","transDate","createdby","createdOn") VALUES (pid,sid,amount,tstatus,tnum,returnCode,remarks,tid,tdate,cby,CURRENT_TIMESTAMP) RETURNING id INTO payid ; 
     return payid;     
END;

 $$ LANGUAGE plpgsql;
/*******Functions for paymentEntry - End********/

/*******Function for editTransport- Start*****/
CREATE OR REPLACE FUNCTION edittransport(did integer, ppoint character(20),dpoint character(20),uid integer,st character(50),amt real) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_transport WHERE lower("pickUp")=lower(ppoint) AND "id" != did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_transport SET "pickUp"=ppoint,"dropDown"=dpoint,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid,"stage"=st,"amount"=amt WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for editTransport - End*****/

/*******Function for Add Transport - Start*****/
CREATE OR REPLACE FUNCTION addtransport(ppoint character(20),dpoint character(100),uid integer,st character(50),amt real) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_transport WHERE lower("pickUp") = lower(ppoint) AND "deleted" = '0' ;      

    IF NOT found THEN
       INSERT INTO public.tbl_transport("pickUp", "dropDown","createdOn","createdBy","stage","amount")
       VALUES (ppoint,dpoint,CURRENT_TIMESTAMP,uid,st,amt);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
/*******Function for Add Transport - End*****/
/********Functions for Class Page- End*******/

/*******Function for updatewavingamount- Start*****/
CREATE OR REPLACE FUNCTION updatewavingamount(cn character(50),newamt real,uid integer,fg character(40),wp integer,wa real,wt real) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_challans WHERE "challanNo"=cn;      

    IF found THEN
        UPDATE tbl_challans SET "org_total"=newamt,"waivedPercentage" = wp, "waivedAmount" = wa, "waivedTotal" = wt, "updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "challanNo" = cn AND "feeGroup" = fg;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
    $$ LANGUAGE plpgsql
    
/*******Function for updatewavingamount - End*****/

/***Function for createlatefeechallan - Start ****/
CREATE OR REPLACE FUNCTION createlatefeechallan(cn character(50),studId character(30),classlist character(5),trm character(5),latefee real,str integer,due date,yr character(10))
  RETURNS int AS $$ 
DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_challans WHERE "studentId" =studId AND "classList" = classlist  AND "term" = trm  AND "stream" = str;
    IF found THEN
      INSERT INTO public.tbl_challans("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear") VALUES (cn,studId,classlist,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,'LATE FEE',yr) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
 $$ LANGUAGE plpgsql;
/***Fubction for createlatefeechallan - End ****/
/***Function for chequerevoke - Start ****/

CREATE OR REPLACE FUNCTION chequerevoke(cno character(50), uid integer) 
  RETURNS int AS $$
  DECLARE cid int;
BEGIN
    PERFORM  1 FROM public.tbl_challans WHERE "challanNo" = cno AND "challanStatus" = '1' LIMIT 1;      

    IF found THEN
      UPDATE tbl_challans SET "pay_type" = null,"bank" = null,"cheque_dd_no" = null,"chequeRemarks" = null,"challanStatus" = '0',"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid, "paid_date" = null WHERE "challanNo" = cno ;

      DELETE FROM tbl_variable_fee_entry WHERE "challanNo" = cno RETURNING id INTO cid;
    ELSE
      cid = 0;
      END IF;

    return cid;
END;
    $$ LANGUAGE plpgsql

/***Function for chequerevoke - Start ****/
/***Function for transportchallancreate - Start ****/

CREATE OR REPLACE FUNCTION transportchallancreate(cno character(50), sid character(50), stgid character(100), cls character(5), term character(5), ss character(15), uid integer, cs integer, tot real, orgtot real, stream integer, due date, feegroup character(40), acayear character(10))
  RETURNS int AS $$
  DECLARE cid int;
BEGIN
    INSERT INTO public.tbl_challans("challanNo", "studentId", "feeTypes", "classList", term, "studStatus", "createdOn", "createdBy", "challanStatus", total, org_total, stream, duedate,"feeGroup","academicYear")
  VALUES (cno, sid, stgid, cls, term, ss, CURRENT_TIMESTAMP, uid, 0, tot, orgtot, stream, due, feegroup, acayear) RETURNING id INTO cid;
   return cid;

END;
    $$ LANGUAGE plpgsql

/***Function for transportchallancreate - Start ****/
/****************FUNCTIONS - END********************/



 /********VIEWS - Start*******/

/****** View for Login - Start*****/
create view loginchk AS SELECT * FROM tbl_parents WHERE "deleted" = 0 AND status = '1';
/****** View for Login - End*****/

/****** This view is for to Fee Entry Report *****/
 create view getpaymentdata AS  SELECT f.id,
    f."studentId",
    f."studentName",
    f."academicYear",
    f.semester,
    f.class,
    f.stream,
    s.section,
    c.class_list,
    str.stream AS streamname,
    f.total,
    f."challanNo"
   FROM tbl_variable_fee_entry f
     LEFT JOIN tbl_student s ON f."studentId" = s."studentId"
     LEFT JOIN tbl_class c ON c.id = f.class::integer
     LEFT JOIN tbl_stream str ON str.id = f.stream::integer
  WHERE f.deleted = 0 AND f.status = '1'::status AND f."transId" <> ''::bpchar
  ORDER BY f.id DESC;
/****** This view is for to Fee Entry report END*****/

/****** This view is for to payment Report *****/
 create view getpaiddata AS  SELECT p."transStatus",
    p."transDate",
    p."transNum",
    p."studentId",
    s."studentName",
    s.section,
    s.class,
    s.stream,
    p.amount,
    par."userName" AS parentname,
    p.id,
    f."challanNo",
    p.remarks
   FROM tbl_payments p
     LEFT JOIN tbl_student s ON p."studentId" = s."studentId"
     LEFT JOIN tbl_variable_fee_entry f ON f."transId"::integer = p.id
     LEFT JOIN tbl_parents par ON p."parentId" = par.id;
/****** This view is for to payment Report- End*****/
 
/****** This view is for to getpaiddatafilter- Start*****/
create view getpaiddatafilter AS SELECT p."transStatus",p."transDate",p."transNum",p."studentId",s."studentName",s."section", s."class",c."class_list",str."stream" AS streamName,
s."stream",p."amount",pr."userName", f."challanNo" from tbl_payments p 
left join tbl_student s on p."studentId" = s."studentId" 
left join tbl_parents pr ON pr."id" = p."parentId"
left join tbl_class c ON c.id=s.class::int
left join tbl_stream str ON str.id = s.stream::int; 
/****** This view is for to getpaiddatafilter- End*****/

 
/****** This view is for to gettempdata - Start*****/
create view gettempdata AS SELECT t."studentId",t."studentName",c."class_list",t."classList",t."stream", t."term", t."studStatus", str."stream" AS streamname,s."section", t."challanNo",s."hostel_need",s."transport_need" 
from tbl_temp_challans t 
left join tbl_student s  ON t."studentId" = s."studentId"
left join tbl_class c ON c.id=t."classList"::int
left join tbl_stream str ON str.id = t.stream::int WHERE t."feeTypes" IS NOT NULL;
/****** This view is for to gettempdata- End*****/

/****** This view is for to waviercheck- Start*****/
 CREATE VIEW waviercheck AS
  SELECT s."studentName",
    cl.class_list,
    st.stream,
    c.term,
    c.total,
    c."studentId",
    c."challanNo",
    c.waived,
    c.org_total,
    c.id,
    c."feeTypes",
    c."feeGroup",
    c."waivedPercentage",
    c."waivedAmount",
    c."waivedTotal",
    s.section,
    s."email",
    c."challanStatus"
   FROM tbl_challans c
     LEFT JOIN tbl_student s ON s."studentId" = c."studentId"
     LEFT JOIN tbl_class cl ON cl.id = c."classList"::integer
     LEFT JOIN tbl_stream st ON st.id = c.stream
  WHERE c.deleted = 0 
  ORDER BY c.id;
/****** This view is for to waviercheck- End*****/


 /****** This view is for settings page *****/
create view fetchstudentdata AS SELECT * FROM tbl_student WHERE "deleted" = 0 AND status = '1';
 /****** This view is for settings page*****/

/***VIEW FOR ADMIN PAGE - Start ****/
CREATE VIEW admincheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "adminName", "adminEmail", "adminPassword"  FROM tbl_admin WHERE "deleted" = 0 ORDER BY "adminName" ASC
/***VIEW FOR ADMIN PAGE - End****/

/****** View for Admin Login - Start*****/
create view adminchk AS SELECT * FROM tbl_admin WHERE "deleted" = 0 AND status = '1';
/****** View for Admin Login - End*****/

/***VIEW FOR PARENT PAGE - Start ****/
 create view parentcheck AS  SELECT
        CASE
            WHEN tbl_parents.status = '1'::status THEN 'ACTIVE'::text
            ELSE 'INACTIVE'::text
        END AS status,
    tbl_parents.id,
    tbl_parents."firstName",
    tbl_parents."lastName",
    tbl_parents."userName",
    tbl_parents.email,
    tbl_parents."secondaryEmail",
    tbl_parents."mobileNumber",
    tbl_parents."secondaryNumber",
    tbl_parents."password"
   FROM tbl_parents
  WHERE tbl_parents.deleted = 0
  ORDER BY tbl_parents."userName";
/***VIEW FOR PARENT PAGE - End****/

/***VIEW FOR STUDENT PAGE - Start ****/
// ***** VIEW FOR studentcheck - Start*****//
 create view studentcheck AS  SELECT
        CASE
            WHEN s.status = '1'::status THEN 'ACTIVE'::text
            ELSE 'INACTIVE'::text
        END AS status,
    s.id,
    s."studentName",
    s.class,
    s.section,
    s.term,
    c.class_list,
    str.stream AS streamname,
    s.stream,
    s."studentId",
    s.email,
    s."mobileNumber",
    s."parentId",
    p."userName",
    s.transport_stg,
    s.hostel_need,
    s.lunch_need,
    s.academic_yr
   FROM tbl_student s
     LEFT JOIN tbl_class c ON c.id = s.class::integer
     LEFT JOIN tbl_stream str ON str.id = s.stream::integer
     LEFT JOIN tbl_parents p ON s."parentId" = p.id
  WHERE s.deleted = 0
  ORDER BY s."studentName";
  
// ***** VIEW FOR studentcheck - End*****//

/***VIEW FOR STUDENT PAGE - End****/

/*** View for fee type - Start *****/
CREATE getfeetypes AS SELECT f.id,
    f."feeType",
    f.description,
    f."createdBy",
    f."updatedBy",
    f.status,
    f.deleted,
    f."createdOn",
    f."updatedOn",
    f.tax,
    f."feeGroup",
    f.mandatory,
    f.applicable,
    g."feeGroup" AS feegroupname
   FROM tbl_fee_type f
     JOIN tbl_fee_group g ON f."feeGroup"::integer = g.id
  WHERE f.deleted = 0 AND f.status = '1'::status;
/***** View for fee type - End ***/

/***VIEW FOR Class PAGE - Start ****/
CREATE VIEW classcheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "class_list", "description","streamId"  FROM tbl_class WHERE "deleted" = 0 ORDER BY "id" ASC
/***VIEW FOR Class PAGE - End****/

/***VIEW FOR FEEGROUP  PAGE - Start ****/
CREATE VIEW feegroupcheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "feeGroup", "description"  FROM tbl_fee_group  WHERE "deleted" = 0 ORDER BY "id" ASC
/***VIEW FOR FEEGROUP  PAGE - End****/

/***VIEW FOR Stream PAGE - Start ****/
CREATE VIEW streamcheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "stream", "description"  FROM tbl_stream WHERE "deleted" = 0 ORDER BY "stream" ASC
/***VIEW FOR Stream PAGE - End****/

/***VIEW FOR Latefee PAGE - Start ****/
CREATE VIEW latefeecheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "noOfDays", "amount"  FROM tbl_late_fee WHERE "deleted" = 0 ORDER BY "id" ASC
/***VIEW FOR Latefee PAGE - End****/

/***VIEW FOR Feetype PAGE - Start ****/
CREATE VIEW feetypecheck AS SELECT
        CASE
            WHEN t.status = '1'::status THEN 'ACTIVE'::text
            ELSE 'INACTIVE'::text
        END AS status,
    t.id,
    t."feeType",
    t.description,
    t.tax,
    g."feeGroup",
    t.mandatory,
    t.applicable
   FROM tbl_fee_type t 
   INNER JOIN tbl_fee_group g On t."feeGroup"::int = g."id"
  WHERE t.deleted = 0
  ORDER BY t.id;
/***VIEW FOR Feetype PAGE - End****/

/***VIEW FOR Tax PAGE - Start ****/
CREATE VIEW taxcheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "effectiveDate", "taxType", "centralTax", "stateTax"  FROM tbl_tax WHERE "deleted" = 0 ORDER BY "id" ASC
/***VIEW FOR Tax PAGE - End****/

/***VIEW FOR Comments PAGE - Start ****/
CREATE VIEW commentscheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "pageName", "comments","startdate","enddate"  FROM tbl_comments WHERE "deleted" = 0 ORDER BY "id" ASC
/***VIEW FOR Comments PAGE - End****/

/***VIEW FOR yearcheck - Start ****/
CREATE VIEW yearcheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "year"  FROM tbl_academic_year WHERE "deleted" = 0 ORDER BY "id" ASC
 /***VIEW FOR yearcheck - End ****/

/***VIEW FOR termData - Start ****/
 create view termData AS SELECT * FROM tbl_semester;
/***VIEW FOR termData - End ****/

/***VIEW FOR tempChallan - Start ****/
  CREATE VIEW tempchallan AS SELECT t."challanNo",
    t."studentId",
    t."feeTypes",
    t."classList",
    t.term,
    t."studStatus",
    t."createdOn",
    t."createdBy",
    t."updatedOn",
    t."updatedBy",
    t.status,
    t.deleted,
    t.total,
    t.stream,
    t."studentName",
    t.remarks,
    t.duedate,
    g."feeGroup",
    t.id
   FROM tbl_temp_challans t
   INNER JOIN tbl_fee_group g ON t."feeGroup"::int = g."id"
  WHERE t.deleted = 0 AND t.status = '1'::status;
/***VIEW FOR tempChallan - End ****/

/***VIEW FOR challanData - Start ****/
 create view challanData AS 
 SELECT s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c."feeTypes",
    c.id AS cid,
    c."studStatus",
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.org_total,
    c."feeGroup",
    t.paid_date,
    t.cheque_dd_no,
    t.pay_type,
    t."academicYear",
    c."updatedOn",
    s."academic_yr",
    c."waivedPercentage",
    c."waivedAmount",
    c."waivedTotal"
   FROM tbl_student s
     LEFT JOIN tbl_challans c ON s."studentId" = c."studentId"
     LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term
     LEFT JOIN tbl_class cl ON c."classList"::integer = cl.id
     LEFT JOIN tbl_stream str ON s.stream::integer = str.id
     LEFT JOIN tbl_variable_fee_entry t ON c."challanNo" = t."challanNo"
  WHERE c.deleted = 0 AND c.status = '1'::status;
/***VIEW FOR challanData - End ****/

// ***** VIEW FOR FILTERFEEDETAILS FOR FEE CONFIGURATION PAGE *****//
CREATE VIEW filterfeedetails AS SELECT f."academicYear",
    f.id,
    t."feeType",
    s.stream,
    c.class_list,
    f.amount,
    f.semester,
    f."dueDate",
    s.id AS strid,
    c.id AS clid
   FROM tbl_fee_configuration f
     LEFT JOIN tbl_fee_type t ON f."feeType"::integer = t.id
     LEFT JOIN tbl_stream s ON f.stream::integer = s.id
     LEFT JOIN tbl_class c ON f.class = c.id
  WHERE f.deleted = 0; 
  // ***** VIEW FOR FILTERFEEDETAILS FOR FEE CONFIGURATION PAGE - End*****//

// ***** VIEW FOR FILTERSTUDENTDETAILS FOR FEE CONFIGURATION PAGE *****//
CREATE VIEW filterfeedetails AS  SELECT
        CASE
            WHEN s.status = '1'::status THEN 'ACTIVE'::text
            ELSE 'INACTIVE'::text
        END AS status,
    s.id,
    s."studentName",
    s.class,
    c.class_list,
    str.stream AS streamname,
    s.section,
    s."studentId",
    s.term,
    p."userName",
    s.stream
   FROM tbl_student s
     LEFT JOIN tbl_class c ON c.id = s.class::integer
     LEFT JOIN tbl_stream str ON str.id = s.stream::integer
     LEFT JOIN tbl_parents p ON s."parentId" = p.id
  WHERE s.deleted = 0
  ORDER BY s."studentName";
  // ***** VIEW FOR FILTERSTUDENTDETAILS FOR FEE CONFIGURATION PAGE - End*****//

/***VIEW FOR feeconigdata Year PAGE - Start ****/
CREATE VIEW feeconigdata AS SELECT f."academicYear",f.id,t."feeType",s.stream,c.class_list,f.amount,f.semester,f."dueDate" FROM tbl_fee_configuration f 
LEFT JOIN tbl_fee_type t ON f."feeType"::int = t.id 
LEFT JOIN tbl_stream s ON f.stream::int = s.id 
LEFT JOIN tbl_class c ON f.class::int = c.id WHERE f.deleted=0;

/***VIEW FOR feeconigdata Year PAGE - End ****/

/***VIEW FOR Getfeetpedata - Start ****/
CREATE VIEW getfeetypedata AS SELECT f.id,
    c."feeType",
    c.amount,
    c.semester,
    c.class,
    c.stream,f."feeType" AS feename,f."feeGroup",c."academicYear"
   FROM tbl_fee_configuration c
     LEFT JOIN tbl_fee_type f ON f.id = c."feeType"::integer
  WHERE f.status = '1'::status AND f.deleted = 0;
  /***VIEW FOR Getfeetypedata - End ****/

// /***VIEW FOR teacherslist - Start ****/
// CREATE VIEW teacherslist AS SELECT * FROM tbl_teachers WHERE "status" = '1' AND "deleted" = 0;
// /***VIEW FOR teacherslist - End ****/

/***VIEW FOR teacherchk - Start ****/
 create view teacherchk AS SELECT * FROM tbl_teachers WHERE "deleted" = 0;
/***VIEW FOR teacherchk- End ****/

/***VIEW FOR addsemesterdata - Start ****/
CREATE VIEW addsemesterdata as SELECT tbl_semester.id,
    tbl_semester.semester
   FROM tbl_semester; 
/***VIEW FOR addsemesterdata - End ****/

/***VIEW FOR getstudentdata - Start ****/
create view getstudentdata AS   
   SELECT s."studentName",
    s."studentId",
    s."parentId",
    c."classList",
    s.section,
    c.term,
    s.status,
    s.deleted,
    s.stream,
    c."challanNo",
    c."feeTypes",
    c."studStatus",
    c."challanStatus",
    cl.class_list,
    c.org_total,
    c."feeGroup"
   FROM tbl_student s
     LEFT JOIN tbl_challans c ON s."studentId" = c."studentId"
     LEFT JOIN tbl_class cl ON cl.id = c."classList"::integer
  WHERE c."studStatus" = 'Prov.Promoted'::bpchar AND s.deleted = 0 AND s.status = '1'::status;
/***VIEW FOR getstudentdata - End ****/


/***VIEW FOR taxtypecheck - Start ****/
CREATE VIEW taxtypecheck AS  SELECT f.id,
    f.tax,
    u."centralTax",
    u."stateTax"
   FROM tbl_fee_type f
     LEFT JOIN tbl_tax u ON u.id::text = ANY (string_to_array(f.tax::text, ','::text));
/***VIEW FOR taxtypecheck - End ****/

/***VIEW FOR getparentdata - Start ****/
create view getparentdata AS SELECT c."challanNo",
    p.id,
    p."userName",
    p.email,
    p."secondaryEmail",
    p."mobileNumber",
    p."secondaryNumber",
    s."studentId",
    c."feeTypes",
    s."studentName",
    s.stream,
    s.class,
    s.term,
    c."feeGroup",
    s.academic_yr
   FROM tbl_parents p
     LEFT JOIN tbl_student s ON p.id = s."parentId"
     LEFT JOIN tbl_challans c ON s."studentId" = c."studentId"
  WHERE p.status = '1'::status AND p.deleted = 0;  ;
/***VIEW FOR getparentdata - End ****/

/***VIEW FOR Class PAGE - Start ****/
CREATE VIEW transportcheck AS SELECT CASE WHEN "status" = '1' THEN 'ACTIVE'
            ELSE 'INACTIVE'
       END  AS status, id, "pickUp", "dropDown", "amount","stage" FROM tbl_transport WHERE "deleted" = 0 ORDER BY "id" ASC
/***VIEW FOR Class PAGE - End****/

/***VIEW FOR Waving PAGE - Start ****/
CREATE VIEW challandatanew AS
SELECT DISTINCT c."feeTypes",
    s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c.id AS cid,
    c."studStatus",
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.org_total,
    c."feeGroup",
    c.cheque_dd_no,
    c.pay_type,
    c.paid_date,
    c."waivedPercentage",
    c."waivedAmount",
    c."waivedTotal",
    s.academic_yr,
    c.bank,
    c."remarks"
   FROM tbl_student s
     LEFT JOIN tbl_challans c ON s."studentId" = c."studentId"
     LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term
     LEFT JOIN tbl_class cl ON c."classList"::integer = cl.id
     LEFT JOIN tbl_stream str ON s.stream::integer = str.id
  WHERE c.deleted = 0 AND c.status = '1'::status;
/***VIEW FOR Waving PAGE - End****/

/***View for getfeedata - Start ****/
create view getfeedata AS  SELECT s."studentName",s."studentId",s."parentId",
c."classList",s."section", c."term",s."status",s."deleted",s."stream",
c."challanNo",f."feeType",cl.class_list,c."duedate"
   FROM tbl_student s
     LEFT JOIN tbl_variable_fee_entry f ON s."studentId" = f."studentId"
   LEFT JOIN tbl_challans c ON c."challanNo" = f."challanNo"
     LEFT JOIN tbl_class cl ON cl.id = c."classList"::integer  
  WHERE  s.deleted = 0 AND s.status = '1'::status; 
/***View for getfeedata - End ****/

/***View for getchallandata - Start ****/
  CREATE VIEW getchallandata AS 
    SELECT c."challanNo",
    c."classList",
    c.stream,
    c.term,
    c.duedate,
    c.total,
    s.section,
    cl.class_list,
    str.stream AS streamname,
    s."studentName",
    c."waivedPercentage",
    c."waivedAmount",
    c."waivedTotal",
    c."feeTypes",
    c.org_total,    
    c."createdOn",
    c."feeGroup",
    c."challanStatus",
    c.pay_type
   FROM tbl_challans c
     LEFT JOIN tbl_student s ON c."studentId" = s."studentId"
     LEFT JOIN tbl_class cl ON cl.id = c."classList"::integer
     LEFT JOIN tbl_stream str ON str.id = c.stream;
/***View for getchallandata - End ****/



/***View for chequedddata - Start ****/

create view chequedddata AS
 SELECT c.id,
    c."challanNo",
    c."studentId",
    c."feeTypes",
    c."classList",
    c.term,
    c."studStatus",
    c."createdOn",
    c."createdBy",
    c."updatedOn",
    c."updatedBy",
    c.status,
    c.deleted,
    c."challanStatus",
    c.total,
    c.waived,
    c.org_total,
    c.stream,
    c.remarks,
    c.duedate,
    c.pay_type,
    c.bank,
    c.cheque_dd_no,
    f."feeGroup" AS feegroupname,
    c."feeGroup",
    c.paid_date,
    c."waivedPercentage",
    c."waivedAmount",
    c."waivedTotal",
    c."academicYear",
    st."studentName",
    cl.class_list,
    s.stream AS streamname,
    st.section
   FROM tbl_challans c
     LEFT JOIN tbl_class cl ON c."classList"::integer = cl.id
     LEFT JOIN tbl_stream s ON c.stream = s.id
     LEFT JOIN tbl_fee_group f ON c."feeGroup"::integer = f.id
     LEFT JOIN tbl_student st ON c."studentId" = st."studentId";
/***View for tbl_teachers_id_seq - End ****/
/********VIEWS - End*******/

insert into tbl_student ("studentName", "studentId", "mobileNumber")  SELECT "child_name", "student_id", "mobile_no"
FROM sc_student_info

/***Alter for tbl_teachers_id_seq - Start ****/
DELETE FROM tbl_class;
ALTER SEQUENCE tbl_class_id_seq RESTART WITH 1;
 /***Alter for tbl_teachers_id_seq - End ****/

 UPDATE tbl_student SET class = sc.sc_standard_id FROM sc_class sc WHERE sc.sc_class_id = tbl_student.class::int;

 ELECT setval('tbl_student_id_seq', max(id))
FROM   tbl_student;