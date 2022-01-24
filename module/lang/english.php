<?php
    //GLOBAL
    define("_CURRENCY", "USD");
    define("_INCOME", "Income");
    define("_DAY", "Day");
    define("_MONTH", "Month");
    define("_YEAR", "Year");
    define("_OR", "or");
    define("_SELL_PROFIT", "Profit");
    define("_LIST", "List");
    define("_TYPE", "Type");
    define("_DATA", "Data");
    define("_YES", "Yes");
    define("_NO", "No");
    define("_CLOSE_THIS_PAGE_WITHOUT_CHANGE", "Close this page without any changes");
    define("_ENABLE_JS_SITE", "https://www.enable-javascript.com/en/");

    //TITLE
    define("_DATA_SYSTEMS", "Data Systems 1");
    define("_ADD_BUY", "Add Buy Info");
    define("_ADD_SELL", "Add Sell Info");
    define("_UNDO_SELL", "Undo Sell");
    define("_ADMIN_PAGE", "Admin Page");
    define("_MONTHLY_SALES_SUMMARY", "Monthly Sales Summary");
    define("_VIEW_ALL_DATA", "View All Data");
    define("_REASSIGN_ID", "Reassign ID");
    define("_EDIT_DATA", "Edit Data");
    define("_DELETE_DATA", "Delete Data");
    define("_PRINT_DATA", "Print Data");
    define("_SETTING", "Settings");
    define("_CHANGELOG", "Changelog");
    define("_ABOUT_THIS", "About This Site");
    define("_TEST", "Test");
    define("_NOTIFY", "Notify");

    //LOGIN TITLE
    define("_LOGIN_ADD_BUY", "Login to Add Buy Info");
    define("_LOGIN_ADD_SELL", "Login to Add Sell Info");
    define("_LOGIN_UNDO_SELL", "Login to Undo Sell");
    define("_LOGIN_ADMIN_PAGE", "Login to Admin Page");
    define("_LOGIN_MONTHLY_SALES_SUMMARY", "Login to View Monthly Sales Summary");
    define("_LOGIN_VIEW_ALL_DATA", "Login to View All Data");
    define("_LOGIN_PRINT_DATA", "Login to Print Data");
    define("_LOGIN_CHANGELOG", "Changelog");

    //LOGIN
    define("_PASSWORD", "Password");
    define("_PLEASE_LOGIN", "Please login!");
    define("_ERROR_READ_PASSWORD_FILE", "<head>
    <link rel=\"shortcut icon\" type=\"img/icon\" href=\"favicon.ico\">
    <link rel=\"stylesheet\" href=\"/main.css\" type=\"text/css\" />
    <title>" . _DATA_SYSTEMS . "</title>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=0.6\">
    </head>
    <body>
    <table border='1'>
    <tr>
    <td class='curve'>
    <div style='padding:28px 28px 28px 28px;'><font size='4'><strong>
    <center>Can't read a password file!</strong><br><img src='/blank.png' height='6'><br>./module/loginpassword.txt</center>
    </font></div>
    </td>
    </tr>
    </table>
    </body>");
    define("_ENTER_PASSWORD_CORRECTLY", "Enter a password correctly!");
    define("_ENTER_BY_VIEW_ALL_DATA_PAGE", "Please enter this page from View All Data page!");
    define("_ENTER_ALL_MONTH_REPORT_BY_PAGE", "You should enter this page from Admin page to choose the year.");
    define("_ENTER_PRINT_VIEW_BY_PAGE", "You should enter this page from View All Data page to choose specific data to print");

    //INTERACT
    define("_CANCEL", "Cancel");
    define("_CONTINUE", "Continue");
    define("_CLOSE_MESSAGE", "Close Message");
    define("_CLOSE_PAGE", "Close Page");
    define("_NEXT", "Next");
    define("_SAVE", "Save");
    define("_BACK_TO_EDIT", "Back To Edit");
    define("_BACK_TO_PREV_PAGE", "Back to Previous Page");
    define("_SET", "Set");
    define("_SEARCH", "Search");
    define("_SHOW", " Show");
    define("_REFRESH", "Refresh");
    define("_PRINT_THIS", "Print This Data");
    define("_REMOVE_SETTING", "Remove Setting");
    define("_EDIT_THIS", "Edit This Data");
    define("_DELETE_THIS", "Delete This Data");
    define("_REASSIGN", "Reassign");
    define("_DELETE", "Delete");
    define("_VISIT_SITE", "Visit Site");
    define("_GOTO_VIEW_ALL_DATA_PAGE", "Go To View All Data Page");

    //ERROR
    define("_ERROR_ENABLE_JS", "Please enable Javascript for full functionality!<br>Press " . _CONTINUE . " to see how to enable Javascript.");
    define("_ERROR_DB_CONNECT", "Can't login to database!");
    define("_ERROR_WHILE_ADD_DATA", "An error occurred while adding data!");
    define("_ERROR_WHILE_UPDATE_DATA", "An error occurred while updating data!");
    define("_ERROR_WHILE_LOAD_DATA", "An error occurred while loading data!");
    define("_ERROR_WHILE_LOAD_DATA_FOR_GRAPH", "An error occurred while loading data for graph!");
    define("_ERROR_WHILE_REASSIGN_ID", "An error occurred while reassigning an ID");
    define("_ERROR_WHILE_DELETE_DATA", "An error occurred while deleting data!");
    define("_ERROR_PROFIT_MORE_THAN", "Please check sell price and profit.<br>(Profit can't more than sell price.)");
    define("_ERROR_ID_NOT_FOUND", "Data not found for ID");
    define("_CANNOT_READ_FILE", "Can't read data from file!");
    define("_ERROR_ILLEGAL_BUY_PRICE", "Please try to enter buy price again! (Check if it is an integer or a decimal.)");
    define("_ERROR_ILLEGAL_SELL_PRICE", "Please try to enter sell price again่! (Check if it is an integer or a decimal.)");
    define("_ERROR_ILLEGAL_ANY_PRICE", "Please try to enter any price again่! (Check if it is an integer or a decimal.)");
    define("_ERROR_ILLEGAL_COMMAND", "Unable to proceed your custom command!");

    //ADD BUY, ADD SELL, EDIT DATA AND UNDO SELL
    define("_COMPLETE_INFO", "Complete an information.");
    define("_BUY_DATE", "Buy Date");
    define("_SELL_DATE", "Sell Date");
    define("_PRODUCT_NAME", "Product Name");
    define("_CATEGORY", "Category");
    define("_BUY_PRICE", "Buy Price");
    define("_SELL_PRICE", "Sell Price");
    define("_CUSTOMER_PROVINCE", "Customer Province");
    define("_CONTACT_FROM", "Contact From");
    //define("_REMARKS", "Remarks"); REMOVED IN 3.1.0
    define("_DELIVERY_COST", "Delivery Cost");
    define("_ENTER_COMPLETE_INFO", "Please enter a complete information!");
    define("_NO_AVALIBLE_PRODUCT_FOUND", "No avalible products found!");
    define("_NO_UNDOABLE_PRODUCT_FOUND", "No undoable products found! (May be due to specify the year that the products have not been sold)");
    define("_CHANGE_SYMBOL", "Please change the symbol");
    define("_IN_PRODUCT_NAME", "to something else in product name!"); /* _CHANGE_SYMBOL SUFFIX */
    //define("_IN_REMARKS", "to something else in remarks!"); /* _CHANGE_SYMBOL SUFFIX */ REMOVED IN 3.1.0
    define("_CANNOT_LONGER_THAN", "can't longer than");
    define("_CHARACTER", "chartacters!"); /* _CANNOT_LONGER_THAN SUFFIX */
    define("_OLD_DATA", "Old Data");
    define("_OLD_DATA_OF_PRODUCT_NAME", "Old Data of Product Name");
    //define("_OLD_DATA_OF_REMARKS", "Old Data of Remarks"); REMOVED IN 3.1.0
    define("_SELECT_PRODUCT_TO_UNDO_SELL", "Select a product that you want to undo sell.");
    define("_SPECIFY_PRODUCT_YEAR", "Specify year of product");
    define("_SUCCESSFULLY_ADD_BUY", "Systems has successfully added buy information,<br>Don't refresh this page!");
    define("_SUCCESSFULLY_ADD_SELL", "Systems has successfully added sell information,<br>Don't refresh this page!");
    define("_SUCCESSFULLY_EDIT_DATA", "Systems has successfully edited data!");
    define("_SUCCESSFULLY_UNDO_SELL", "Systems has successfully undone sell,<br>Don't refresh this page!");
    define("_WARN_LITE_MODE_ON", "If you click the save button and no message appears, not this message<br>For you to try to turn off data saving mode And save again");
    
    //ADMIN AND ALL MONTH REPORT
    define("_TODAY_SELL", "Today sells");
    define("_TODAY_INCOME", "Today income");
    define("_TODAY_PROFIT", "Today profit");
    define("_THIS_MONTH_SELL", "This month sells");
    define("_THIS_MONTH_INCOME", "This month income");
    define("_THIS_MONTH_PROFIT", "This month profit");
    define("_THIS_YEAR_SELL", "This year sells");
    define("_THIS_YEAR_INCOME", "This year income");
    define("_THIS_YEAR_PROFIT", "This year profit");
    define("_YESTERDAY_INCOME", "Yesterday Income");
    define("_LAST_MONTH_INCOME", "Last Month Income");
    define("_LAST_YEAR_INCOME", "Last Year Income");
    define("_THIS_YEAR_DATA", "This Year's Data");
    define("_PRINCIPAL", "Principal");
    define("_VALUE", "Value");
    define("_ALL_PRODUCT_IN_SYSTEM", "All products in the systems");
    define("_AVALIBLE_PRODUCT", "Avalible product in stock"); /* GRAPH TITLE */
    define("_NOT_AVALIBLE_PRODUCT", "Product not available in stock");
    define("_NOT_AVALIBLE_PRODUCT", "Product not available in stock");
    define("_TOTAL_DELIVERY_COST", "Total delivery cost");
    define("_VIEW_ALL_MONTH_REPORT", "View monthly sales summary, Enter year");
    define("_CURRENTLY_VIEW_YEAR", "Currently View Year");
    define("_JANUARY", "January");
    define("_FEBUARY", "Febuary");
    define("_MARCH", "March");
    define("_APRIL", "April");
    define("_MAY", "May");
    define("_JUNE", "June");
    define("_JULY", "July");
    define("_AUGUST", "August");
    define("_SEPTEMBER", "September");
    define("_OCTOBER", "October");
    define("_NOVEMBER", "November");
    define("_DECEMBER", "December");
    define("_TOTAL_FIRST_QUARTER", "Total 1st Quarter");
    define("_TOTAL_SECOND_QUARTER", "Total 2nd Quarter");
    define("_TOTAL_THIRD_QUARTER", "Total 3rd Quarter");
    define("_TOTAL_FORTH_QUARTER", "Total 4th Quarter");
    define("_TOTAL", "Total");
    define("_AVERAGE_SALARY_FIRST_QUARTER", "Average Salary of 1st Quarter");
    define("_AVERAGE_SALARY_SECOND_QUARTER", "Average Salary of 2nd Quarter");
    define("_AVERAGE_SALARY_THIRD_QUARTER", "Average Salary of 3rd Quarter");
    define("_AVERAGE_SALARY_FORTH_QUARTER", "Average Salary of 4th Quarter");
    define("_AVERAGE_SALARY_ALL_YEAR", "Average Salary of The Year");
    define("_ALL_MONTH_REPORT_OF_YEAR", "Monthly Sales of The Year"); /* GRAPH TITLE */

    //VIEW, REASSIGN-ID, DELETE DATA AND PRINT VIEW
    define("_HIDE_CONTROL_PANEL", "Hide Control Panel");
    define("_SHOW_CONTROL_PANEL", "Show Control Panel");
    define("_SELECT_ORDER_MODE", "Select Order Mode");
    define("_SELECT_LIST", "Select List");
    define("_SET_VIEW_BUY_DAY_MONTH", "Set the Products Buy Day/Month");
    define("_SET_VIEW_SELL_DAY_MONTH", "Set the Products Sell Day/Month");
    define("_SEARCH_PRODUCT_NAME", "Search Product Name");
    define("_CUSTOM_COMMAND", "Custom Command (Advanced)");
    define("_CURRENT_ORDER_MODE", "Currently Order Mode");
    define("_ALL_DATA", "All Data");
    define("_IS_AVALIBLE", "Avalible");
    define("_THIS_PAGE_TOTAL", "Total This Page");
    define("_THIS_PAGE_COUNT", "This Page have");
    define("_PAGE", "Page");
    define("_WARN_NO_DATA_FOUND", "No Data Found");
    define("_REASSIGN_ID_ASK", "Are you sure you want to reassign the ID?");
    define("_REASSIGN_ID_EXPLAIN", "Systems will reassign all products ID.<br><img src=\"/blank.png\" height=\"12\" /><br>If you accidentally delete a product that is between other products,<br>without choosing to reassign the ID<br>If not having a lot of data and the server is not slow, We recommend to continue.");
    define("_REASSIGN_ID_EXPLAIN_IN_DELETE_DATA_PAGE", "Systems will reassign all products ID.<br><img src=\"/blank.png\" height=\"12\" /><br>If you are delete a product that is between other products<br>We recommended you to reassign the ID,<br>But if Systems have a lots of data or server is slow. We don't recommended.");
    define("_SUCCESSFULLY_RESSIGN_ID", "Systems has successfully reassign the ID!");
    define("_DELETE_DATA_ASK", "Are you sure you want to delete data?");
    
    define("_SUCCESSFULLY_DELETE_DATA_BUT_REASSIGN_ID_ERROR", "Systems has successfully delete data,<br>But error occurred while reassigning an ID!");
    define("_SUCCESSFULLY_DELETE_DATA_AND_REASSIGN_ID", "Systems has successfully delete data and reassign the ID!");
    define("_SUCCESSFULLY_DELETE_DATA", "Systems has successfully delete data!");
    define("_VERSION", "Verison");
    define("_LASTEST", "Lastest");
    define("_LOAD_GRAPH", "Load Graph");
    define("_LOAD_GRAPH_ALL_MONTH_REPORT", "Load Graph Monthly Sales Summary");
    define("_PRINT_INFO_DESKTOP", "Set the margin on each side to 2mm or 0.08\".<br><img src='/blank.png' height='2' /><br>Set scale to 100 for the beauty of printing data.");
    define("_PRINT_INFO_MOBILE", "When you finish printing, If you want to print again,<br>click the logo to show button again!");
?>