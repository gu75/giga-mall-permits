@extends('layouts.app')

@section('title', 'Terms & Conditions / Mall SOP')

@section('content')
<div class="max-w-4xl mx-auto py-4">
    <h1 class="text-2xl font-bold mb-6 text-[#0A2342]">Mall SOP — Terms & Conditions</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-[#E2E8F0] p-6">
            <h2 class="font-bold text-[#0A2342] mb-4">English</h2>
            <p class="text-sm text-gray-700 mb-4">
                The purpose of this SOP is to prevent mismanagement, risks, and other financial losses related to work being carried out on-site.
            </p>
            <ol class="list-decimal list-inside space-y-3 text-sm text-gray-700">
                <li>Smoking is prohibited inside the mall without permission. Before entering the mall, lighters and matchboxes will be checked and confiscated. In case of violation, a fine of Rs. 10,000 will be imposed, and the work may also be suspended.</li>
                <li>The tenant/contractor must appoint a designated supervisor over their workers at all times; work must not proceed until the HSE officer and shift in-charge have given clearance.</li>
                <li>Flammable materials (oil cans, cardboard, polythene sheets) must not be stored at the worksite; proper cleanliness must be maintained.</li>
                <li>Avoid loose or worn-out wires, cable connections, and substandard electrical machinery. After work is completed, the responsible person must safely store all used wires and tools, arranged properly to prevent damage.</li>
                <li>Child labor is prohibited by law — employing anyone under 18 years of age is a criminal offense.</li>
                <li>The contractor carrying out construction/repair work is responsible for their workers and their conduct.</li>
                <li>The mall's central systems (fire alarm system, AC, CCTV, firefighting equipment, and electrical installations) must never be tampered with. They must not be damaged; any damage caused must be repaired by the responsible party.</li>
                <li>A permit is mandatory for any temporary/hot work involving open flame or where hazards may arise, such as cutting, welding, etc.</li>
                <li>
                    All workers must use safety equipment during work. Depending on the task:
                    <ol class="list-decimal list-inside ml-4 mt-1 space-y-1">
                        <li>Electrical work — safety gloves, safety shoes, and clear safety goggles</li>
                        <li>Mason/carpenter work — safety gloves, safety shoes, safety mask, and full face shield</li>
                        <li>Working at height — a safety belt is mandatory if the height exceeds 6 feet</li>
                        <li>Paint work — chemical handling gear, safety belt, and safety shoes</li>
                    </ol>
                </li>
                <li>For any questions, the HSC/HSE representative on duty must be contacted.</li>
                <li>In case of any violation, HSE, the Area Manager, or Security officers may take appropriate action.</li>
                <li>If an employee repeatedly violates the rules, they will first be counseled/warned; further disciplinary action may be taken by management if needed.</li>
                <li>If any outlet/shop repeatedly causes financial loss to the mall or its property through negligence, a fine may be imposed on that shop/outlet according to the prescribed rules.</li>
            </ol>

            <div class="mt-6 rounded-lg bg-amber-50 border border-amber-200 p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-800 mb-2">Acknowledgment</p>
                <p class="text-sm text-amber-900">
                    I confirm that I have been fully explained this SOP, understood it, and agree to comply with it while carrying out this work. In case of any damage caused by my crew, I will be responsible for it — whether that responsibility is financial, legal (fines, penalties), or other legal action.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#E2E8F0] p-6" dir="rtl">
            <h2 class="font-bold text-[#0A2342] mb-4">اردو</h2>
            <p class="text-sm text-gray-700 mb-4">
                اس SOP کا مقصد جگہ پر ہونے والے کام سے متعلق بدانتظامی، خطرات اور دیگر مالی نقصانات کو روکنا ہے۔
            </p>
            <ol class="list-decimal list-inside space-y-3 text-sm text-gray-700">
                <li>مال کے اندر اجازت کے بغیر سگریٹ نوشی ممنوع ہے۔ مال میں داخل ہونے سے قبل چوکیوں پر لائٹرز اور ماچس چیک کر کے ضبط کر لیے جائیں گے۔ خلاف ورزی کی صورت میں دس ہزار روپے (10,000) جرمانہ عائد کیا جائے گا اور کام بھی معطل کیا جا سکتا ہے۔</li>
                <li>کرایہ دار/کنٹریکٹر اپنے ورکرز پر ہر وقت ایک مقررہ سپروائزر رکھے گا۔ کام اس وقت تک شروع نہیں کیا جائے گا جب تک HSE آفیسر اور شفٹ انچارج اجازت نہ دے دیں۔</li>
                <li>آتش گیر مواد (تیل کے کین، گتے، پولی تھین شیٹس) کام کی جگہ پر نہ رکھے جائیں؛ صفائی کا خیال رکھا جائے۔</li>
                <li>ڈھیلے/بوسیدہ تاروں، کیبل کنکشنز اور غیر معیاری مشینری سے پرہیز کریں۔ کام مکمل ہونے کے بعد ذمہ دار شخص تمام استعمال شدہ تاروں اور اوزاروں کو مناسب ترتیب سے محفوظ کرے۔</li>
                <li>چائلڈ لیبر قانوناً ممنوع ہے اور 18 سال سے کم عمر افراد کو کام پر رکھنا قانوناً جرم ہے۔</li>
                <li>تعمیراتی/مرمت کا کام کرنے والا کنٹریکٹر اپنے ورکرز اور ان کے طرزِ عمل کا ذمہ دار ہوگا۔</li>
                <li>مال کے مرکزی نظام (فائر الارم سسٹم، اے سی، سی سی ٹی وی، فائر فائٹنگ آلات، اور بجلی کی تنصیبات) سے کبھی چھیڑ چھاڑ نہ کی جائے۔ انہیں نقصان نہیں پہنچانا چاہیے؛ اگر نقصان ہو جائے تو ذمہ دار پارٹی مرمت کروائے گی۔</li>
                <li>کھلی آگ یا خطرہ پیدا کرنے والے عارضی/ہاٹ ورک (جیسے کٹنگ، ویلڈنگ وغیرہ) کے لیے پرمٹ لازمی ہے۔</li>
                <li>
                    کام کے دوران تمام ورکرز حفاظتی سامان استعمال کریں۔ کام کی نوعیت کے مطابق:
                    <ol class="list-decimal list-inside mr-4 mt-1 space-y-1">
                        <li>الیکٹریکل کام — حفاظتی دستانے، حفاظتی جوتے، اور شفاف حفاظتی چشمیں</li>
                        <li>مستری/کارپینٹر کا کام — حفاظتی دستانے، حفاظتی جوتے، حفاظتی ماسک، اور مکمل فیس شیلڈ</li>
                        <li>اونچائی پر کام — 6 فٹ سے زیادہ اونچائی پر سیفٹی بیلٹ لازمی ہے</li>
                        <li>پینٹ کا کام — کیمیکل ہینڈلنگ گیئر، سیفٹی بیلٹ، اور حفاظتی جوتے</li>
                    </ol>
                </li>
                <li>کسی بھی سوال کی صورت میں ڈیوٹی پر موجود HSC/HSE نمائندے سے رابطہ کیا جائے۔</li>
                <li>کسی بھی خلاف ورزی کی صورت میں HSE، ایریا مینیجر، یا سیکیورٹی افسر مناسب کارروائی کر سکتے ہیں۔</li>
                <li>اگر ملازم بار بار قواعد کی خلاف ورزی کرے تو پہلے اسے سمجھایا/تنبیہ کی جائے گی؛ ضرورت پڑنے پر انتظامیہ مزید کارروائی کر سکتی ہے۔</li>
                <li>اگر کوئی آؤٹ لیٹ/دکان بے احتیاطی سے مال یا اس کی املاک کو بار بار مالی نقصان پہنچائے تو مقررہ قواعد کے مطابق اس دکان/آؤٹ لیٹ پر جرمانہ عائد کیا جا سکتا ہے۔</li>
            </ol>

            <div class="mt-6 rounded-lg bg-amber-50 border border-amber-200 p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-800 mb-2">اعتراف</p>
                <p class="text-sm text-amber-900">
                    میں تصدیق کرتا/کرتی ہوں کہ مجھے یہ SOP مکمل طور پر سمجھا دی گئی ہے، میں نے اسے سمجھ لیا ہے، اور اس کام کو انجام دیتے ہوئے اس پر عمل کرنے پر رضامند ہوں۔ اگر میرے عملے کی وجہ سے کوئی نقصان ہو، تو میں اس کا ذمہ دار ہوں گا/گی — چاہے وہ ذمہ داری مالی ہو، قانونی (جرمانہ، سزا) ہو، یا کوئی دیگر قانونی کارروائی ہو۔
                </p>
            </div>
        </div>
    </div>

<div class="mt-6 text-center">
        <a href="{{ url()->previous() !== request()->url() ? url()->previous() : route('dashboard') }}" 
           onclick="if (window.opener || window.history.length <= 1) { window.close(); return false; }" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0A2342] text-white text-sm font-semibold rounded-lg hover:bg-[#07172c] transition shadow-sm">
            ← Close / Go Back
        </a>
    </div>
</div>
@endsection