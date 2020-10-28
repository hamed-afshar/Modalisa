<template>
  <div class="table-back">
    <table class="table-design">
      <thead class="border-b">
      <tr>
        <th class="table-header-cell">{{ '#' }}</th>
        <th class="table-header-cell">{{ $t('translate.id') }}</th>
        <th class="table-header-cell">{{ $t('translate.date') }}</th>
        <th class="table-header-cell">{{ $t('translate.time') }}</th>
        <th class="table-header-cell">{{ $t('translate.picture') }}</th>
        <th class="table-header-cell">{{ $t('translate.price') }}</th>
        <th class="table-header-cell">{{ $t('translate.quantity') }}</th>
        <th class="table-header-cell">{{ $t('translate.size') }}</th>
        <th class="table-header-cell">{{ $t('translate.color') }}</th>
        <th class="table-header-cell">{{ $t('translate.weight') }}</th>
        <th class="table-header-cell">{{ $t('translate.status') }}</th>
        <th class="table-header-cell">{{ $t('translate.country') }}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="order in orders" v-bind:key="order.id" class="table-body-row">
        <td class="table-body-cell"> 1 </td>
        <td class="table-body-cell"> 3200 </td>
        <td class="table-body-cell"> 2019 </td>
        <td class="table-body-cell"> 10:55 </td>
        <td class="table-body-cell"> pic </td>
        <td class="table-body-cell"> 99.99 </td>
        <td class="table-body-cell"> 2 </td>
        <td class="table-body-cell"> Small </td>
        <td class="table-body-cell"> Red </td>
        <td class="table-body-cell"> 200 </td>
        <td class="table-body-cell"> Done </td>
        <td class="table-body-cell"> Turkey </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "orders-table",
  props: {},
  data() {
    return {
      orders:[1,2,3,4,5,6,7,8,9,10]
    }
  }
}
</script>

<style scoped>

</style>