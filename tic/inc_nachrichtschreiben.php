<CENTER>
    <FORM ACTION="./main.php" METHOD="POST">
        <INPUT TYPE="hidden" NAME="modul" VALUE="nachrichten">
        <INPUT TYPE="hidden" NAME="action" VALUE="nachrichtschreiben">

    <TABLE>
      <TR>
        <TD BGCOLOR=#333333><font color="#FFFFFF" size="-1"><B>Nachricht schreiben</B></font></TD>
      </TR>
      <TR>
        <TD>
          <P CLASS="hell"><font size="-1">Titel:
            <INPUT TYPE="text" NAME="txtTitel" MAXLENGTH=50 SIZE=50>
            </font></P>
        </TD>
      </TR>
      <TR>
        <TD>
          <P CLASS="hell"> <font size="-1">
            <TEXTAREA NAME="txtText" COLS=50 ROWS=15></TEXTAREA>
            </font></P>
        </TD>
      </TR>
      <TR>
        <TD>
          <P CLASS="hell"><font size="-1">
            <INPUT TYPE="submit" VALUE="Abschicken">
            </font></P>
        </TD>
      </TR>
    </TABLE>
    </FORM>
</CENTER>
