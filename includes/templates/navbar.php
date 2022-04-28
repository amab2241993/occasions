    </head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="Dashboard.php">الوسامة</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="../dashboard/dashboard.php">متابعة الحجوزات</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            الحجوزات
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../bockings/bockingFinal.php">الحجوزات المؤكدة</a>
                            <a class="dropdown-item" href="../bockings/bockingFirst.php">الحجوزات المبدئية</a>
                            <a class="dropdown-item" href="../bockings/bockingLate.php">الحجوزات مؤجل</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            المخازن
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../stores/cashing.php">اذن صرف</a>
                            <a class="dropdown-item" href="../stores/goods.php">بضاعة اول المدة</a>
                            <a class="dropdown-item" href="../stores/storeCheck.php">فحص المخزن</a>
                            <a class="dropdown-item" href="../stores/damaged.php">ترحيل بين المخازن</a>
                            <a class="dropdown-item" href="#">الفواتير غير المسترجعة</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  href="../bills_expenses/billExpense.php">مصروفات فواتير</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  href="../bills_returns/billReturn.php">مردودات فواتير</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  href="../expenses/expense.php">المصروفات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../ledgers/ledger.php">دفتر اليومية</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            التقارير
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../reports/monthReport.php">التقارير الشهرية</a>
                            <a class="dropdown-item" href="../reports/warehouses.php">تقرير المستودعات</a>
                            <a class="dropdown-item" href="../reports/dfar.php">تقرير الدفار</a>
                            <a class="dropdown-item" href="../reports/syedAhmed.php">تقرير سيد احمد</a>
                            <a class="dropdown-item" href="../reports/nourAldaem.php">تقرير نور الدائم</a>
                            <a class="dropdown-item" href="../reports/responsibility.php">تقرير المسؤلية المجتمعية</a>
                            <a class="dropdown-item" href="../reports/general.php">تقرير المصروفات العامة</a>
                            <a class="dropdown-item" href="../reports/fixed.php">تقرير المصروفات الثابتة</a>
                            <a class="dropdown-item" href="../reports/treasury_bank.php">تقرير الخزنة و البنك</a>
                            <a class="dropdown-item" href="../reports/treasury_bank_default.php">تقرير الخزنة و البنك الإفتراضيين</a>
                            <a class="dropdown-item" href="#">تقرير الموظفيين</a>
                            <a class="dropdown-item" href="#">تقرير الزملاء</a>
                            <a class="dropdown-item" href="#">تقرير الارصدة</a>
                            <a class="dropdown-item" href="../reports/profits.php">بند الارباح</a>
                            <a class="dropdown-item" href="#">تسوية حسابات الزملاء</a>
                            <a class="dropdown-item" href="#">تقرير العملاء</a>
                            <a class="dropdown-item" href="#">تقربر المديونيات</a>
                            <a class="dropdown-item" href="#">تقرير المبيعات</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            الاعدادات
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="../settings/mainCategories.php">الأصناف الرئيسية</a>
                            <a class="dropdown-item" href="../settings/subCategories.php">الأصناف الفرعية</a>
                            <a class="dropdown-item" href="../settings/offices.php">المكاتب</a>
                            <a class="dropdown-item" href="../settings/users.php">المستخدمين</a>
                            <a class="dropdown-item" href="../settings/permissions.php">صلاحيات المستخدمين</a>
                            <a class="dropdown-item" href="../settings/employees.php">الموظفيين</a>
                            <a class="dropdown-item" href="../settings/employeeSalary.php">الموظفيين و المرتبات</a>
                            <a class="dropdown-item" href="../settings/offices.php">صرف المرتبات</a>
                            <a class="dropdown-item" href="../settings/offices.php">إدارة السلفيات</a>
                            <a class="dropdown-item" href="../settings/offices.php">السلفيات</a>
                            <a class="dropdown-item" href="../settings/offices.php">سداد السلفيات</a>
                            <a class="dropdown-item" href="../settings/offices.php">تقرير السلفيات</a>
                            <a class="dropdown-item" href="../settings/offices.php">المشرفين والادارية</a>
                            <a class="dropdown-item" href="../settings/offices.php">نسخ إحتياطي</a>
                            <a class="dropdown-item" href="../settings/controllers.php">إدارة البنود</a>
                            <a class="dropdown-item" href="../settings/offices.php">شيكات تحت التحصيل</a>
                            <a class="dropdown-item" href="../settings/offices.php">تعديل كلمة السر</a>
                            <a class="dropdown-item" href="../settings/offices.php">إدارة العملاء</a>
                            <a class="dropdown-item" href="../settings/sales.php">المبيعات</a>
                            <a class="dropdown-item" href="../settings/offices.php">التحويل بين الحسابات</a>
                            <a class="dropdown-item" href="../settings/deleteAll.php">حذف بيانات النظام</a>
                            <a class="dropdown-item" href="../login/logout.php">تسجيــل خروج</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <h3><?=getH3()?></h3>
        <div class="sitting">