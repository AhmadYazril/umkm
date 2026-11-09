<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardChartAndOrderRecapTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_dashboard_displays_financial_sales_chart_by_default(): void
    {
        $admin = $this->createAdmin();

        Order::create([
            'code' => 'NCM-CHART-001',
            'customer_name' => 'Andi Pratama',
            'customer_phone' => '081234567890',
            'order_type' => 'dine_in',
            'subtotal' => 75000,
            'total' => 75000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('showDashboardChart', true);
        $response->assertViewHas('hideDashboardChart', false);

        $chartData = $response->viewData('financialChart');
        $this->assertIsArray($chartData);
        $this->assertArrayHasKey('daily', $chartData);
        $this->assertArrayHasKey('weekly', $chartData);
        $this->assertArrayHasKey('monthly', $chartData);
        $this->assertSame(75000, $chartData['daily']['summary']['total_revenue']);

        $response->assertSee('id="dashboardFinancialChartSection"', false);
        $response->assertSee('Grafik Keuangan Penjualan');
        $response->assertSee('id="financialSalesCanvas"', false);
        $response->assertSee('switchDashboardChartPeriod', false);
    }

    public function test_admin_can_hide_dashboard_chart_in_settings_and_chart_disappears_and_does_not_run(): void
    {
        $admin = $this->createAdmin();

        Order::create([
            'code' => 'NCM-CHART-002',
            'customer_name' => 'Budi Hartono',
            'customer_phone' => '081234567899',
            'order_type' => 'dine_in',
            'subtotal' => 50000,
            'total' => 50000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        // Hide chart via Settings Update
        $updateResponse = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'cafe_name' => 'Nucomu Cafe',
            'hide_dashboard_chart' => '1',
        ]);

        $updateResponse->assertRedirect();
        $this->assertSame('1', Setting::get('hide_dashboard_chart'));
        $this->assertSame('0', Setting::get('show_dashboard_chart'));

        // Visit Dashboard -> Chart must be completely absent and financialChart must be null (not executed)
        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));

        $dashboardResponse->assertOk();
        $dashboardResponse->assertViewHas('showDashboardChart', false);
        $dashboardResponse->assertViewHas('hideDashboardChart', true);
        $this->assertNull($dashboardResponse->viewData('financialChart'));

        $dashboardResponse->assertDontSee('id="dashboardFinancialChartSection"', false);
        $dashboardResponse->assertDontSee('id="financialSalesCanvas"', false);
        $dashboardResponse->assertDontSee('switchDashboardChartPeriod', false);

        // Re-enable chart via quick toggle route
        $toggleResponse = $this->actingAs($admin)->post(route('admin.settings.toggleChart'), [
            'hide_dashboard_chart' => '0',
        ]);

        $toggleResponse->assertRedirect();
        $this->assertSame('0', Setting::get('hide_dashboard_chart'));
        $this->assertSame('1', Setting::get('show_dashboard_chart'));

        $dashboardAfterEnable = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboardAfterEnable->assertOk();
        $dashboardAfterEnable->assertViewHas('showDashboardChart', true);
        $this->assertNotNull($dashboardAfterEnable->viewData('financialChart'));
        $dashboardAfterEnable->assertSee('id="dashboardFinancialChartSection"', false);
    }

    public function test_admin_can_download_pdf_recap_for_daily_weekly_and_monthly_periods(): void
    {
        $admin = $this->createAdmin();
        $today = Carbon::today();

        // Order today
        Order::create([
            'code' => 'NCM-PDF-TODAY',
            'customer_name' => 'Pelanggan Hari Ini',
            'customer_phone' => '081111222333',
            'order_type' => 'dine_in',
            'subtotal' => 45000,
            'total' => 45000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        // 1. Daily PDF download
        $dailyRes = $this->actingAs($admin)->get(route('admin.orders.export', [
            'period' => 'daily',
            'date' => $today->toDateString(),
            'format' => 'pdf',
        ]));

        $dailyRes->assertOk();
        $dailyRes->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('rekap-transaksi-harian-'.$today->toDateString().'.pdf', (string) $dailyRes->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-1.4', $dailyRes->getContent());
        $this->assertStringContainsString('NCM-PDF-TODAY', $dailyRes->getContent());
        $this->assertStringContainsString('Pelanggan Hari Ini', $dailyRes->getContent());

        // 2. Weekly PDF download
        $weeklyRes = $this->actingAs($admin)->get(route('admin.orders.export', [
            'period' => 'weekly',
            'week_date' => $today->toDateString(),
            'format' => 'pdf',
        ]));

        $weeklyRes->assertOk();
        $weeklyRes->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('rekap-transaksi-mingguan-', (string) $weeklyRes->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-1.4', $weeklyRes->getContent());
        $this->assertStringContainsString('NCM-PDF-TODAY', $weeklyRes->getContent());

        // 3. Monthly PDF download
        $monthlyRes = $this->actingAs($admin)->get(route('admin.orders.export', [
            'period' => 'monthly',
            'month' => $today->format('Y-m'),
            'format' => 'pdf',
        ]));

        $monthlyRes->assertOk();
        $monthlyRes->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('rekap-transaksi-bulanan-'.$today->format('Y-m').'.pdf', (string) $monthlyRes->headers->get('Content-Disposition'));
        $this->assertStringStartsWith('%PDF-1.4', $monthlyRes->getContent());
        $this->assertStringContainsString('NCM-PDF-TODAY', $monthlyRes->getContent());
    }

    public function test_admin_can_open_print_view_and_download_csv_recap(): void
    {
        $admin = $this->createAdmin();
        $today = Carbon::today();

        Order::create([
            'code' => 'NCM-CSV-001',
            'customer_name' => 'Rina Kusuma',
            'customer_phone' => '085678901234',
            'order_type' => 'take_away',
            'subtotal' => 60000,
            'total' => 60000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        // Print preview HTML
        $printRes = $this->actingAs($admin)->get(route('admin.orders.export', [
            'period' => 'daily',
            'date' => $today->toDateString(),
            'format' => 'print',
        ]));

        $printRes->assertOk();
        $printRes->assertSee('LAPORAN REKAP TRANSAKSI');
        $printRes->assertSee('NCM-CSV-001');
        $printRes->assertSee('Rina Kusuma');

        // CSV Download
        $csvRes = $this->actingAs($admin)->get(route('admin.orders.export', [
            'period' => 'monthly',
            'month' => $today->format('Y-m'),
            'format' => 'csv',
        ]));

        $csvRes->assertOk();
        $this->assertStringContainsString('text/csv', (string) $csvRes->headers->get('Content-Type'));
        $this->assertStringContainsString('NCM-CSV-001', $csvRes->streamedContent());
    }
}
