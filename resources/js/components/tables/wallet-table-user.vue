<template>
<div class="table-back">
  <table class="table-design">
    <thead class="border-b">
    <tr>
      <th class="table-header-cell"> {{ '#' }}</th>
      <th class="table-header-cell"> {{ $t('translate.id') }}</th>
      <th class="table-header-cell"> {{ $t('translate.amount') }}</th>
      <th class="table-header-cell"> {{ $t('translate.currency') }}</th>
      <th class="table-header-cell"> {{ $t('translate.date') }}</th>
      <th class="table-header-cell"> {{ $t('translate.time') }}</th>
      <th class="table-header-cell"> {{ $t('translate.picture') }}</th>
      <th class="table-header-cell"> {{ $t('translate.description') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="transaction in transactions" v-bind:key="transaction.id" class="table-body-row">
      <td class="table-body-cell">1 </td>
      <td class="table-body-cell">5000 </td>
      <td class="table-body-cell">TL</td>
      <td class="table-body-cell">2020 </td>
      <td class="table-body-cell">11:20 </td>
      <td class="table-body-cell">pic </td>
      <td class="table-body-cell">my first transction for dear modalisa </td>
    </tr>
    </tbody>
  </table>
</div>
</template>

<script>
export default {
  name: "wallet-table-user",
  props: {},
  data() {
    return {
      transactions:[1,2,3,4,5,6,7,8,9,10]
    }
  }
}
</script>

<style scoped>

</style>