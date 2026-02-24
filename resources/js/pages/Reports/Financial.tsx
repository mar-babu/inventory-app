import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

interface Props {
  from: string;
  to: string;
  totalRevenue?: number;
  totalSales?: number;
  totalVat?: number;
  totalExpense?: number;
  netProfit?: number;
}

export default function Financial({
  from,
  to,
  totalRevenue = 0,
  totalSales = 0,
  totalVat = 0,
  totalExpense = 0,
  netProfit = 0,
}: Props) {
  const [dateFrom, setDateFrom] = useState(from);
  const [dateTo, setDateTo] = useState(to);

  const handleFilter = () => {
    router.get('/reports/financial', { from: dateFrom, to: dateTo }, {
      preserveState: true,
      preserveScroll: true,
    });
  };

  const formatCurrency = (value: number | undefined) =>
    (value ?? 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

  return (
    <>
      <Head title="Financial Report" />

      <div className="p-8 max-w-7xl mx-auto">
        <h1 className="text-3xl font-bold mb-8 text-gray-900 dark:text-white">
          Financial Report
        </h1>

        {/* date filter */}
        <div className="flex flex-col sm:flex-row gap-4 mb-8">
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              From
            </label>
            <input
              type="date"
              value={dateFrom}
              onChange={(e) => setDateFrom(e.target.value)}
              className="border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              To
            </label>
            <input
              type="date"
              value={dateTo}
              onChange={(e) => setDateTo(e.target.value)}
              className="border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
            />
          </div>

          <button
            onClick={handleFilter}
            className="mt-6 sm:mt-0 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded transition"
          >
            Apply Filter
          </button>
        </div>

        {/* stats cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
              Total Revenue
            </h3>
            <p className="text-3xl font-bold text-green-600 dark:text-green-400">
              {formatCurrency(totalRevenue)} TK
            </p>
          </div>

          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
              Net Sales
            </h3>
            <p className="text-3xl font-bold">
              {formatCurrency(totalSales)} TK
            </p>
          </div>

          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
              Total VAT
            </h3>
            <p className="text-3xl font-bold text-purple-600 dark:text-purple-400">
              {formatCurrency(totalVat)} TK
            </p>
          </div>

          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
            <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
              Total Expense (COGS)
            </h3>
            <p className="text-3xl font-bold text-red-600 dark:text-red-400">
              {formatCurrency(totalExpense)} TK
            </p>
          </div>
        </div>

        {/* net profit */}
        <div className="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-900 p-8 rounded-xl shadow text-center">
          <h2 className="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">
            Net Profit
          </h2>
          <p className="text-5xl font-extrabold text-indigo-600 dark:text-indigo-400">
            {formatCurrency(netProfit)} TK
          </p>
        </div>
      </div>
    </>
  );
}