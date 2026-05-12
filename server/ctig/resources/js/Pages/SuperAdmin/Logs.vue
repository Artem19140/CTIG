<script setup lang="ts">
import BaseList from '@/components/BaseComponents/BaseList/BaseList.vue';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';
import BasePaginatedTable from '@/components/BaseComponents/BasePaginatedTable/BasePaginatedTable.vue';
import DetailsDropdown from '@/components/UI/DetailsDropdown/DetailsDropdown.vue';
import { DateFormatter } from '@/helpers/DateFormatter';
import { ActivityLog } from '@/interfaces/ActivityLog';
import { Paginated } from '@/interfaces/Interfaces';
import EmployeeLayout from '@/layouts/EmployeeLayout.vue';

defineOptions({
  layout: [EmployeeLayout],
})

const props = defineProps<{
    activityLogs:Paginated<ActivityLog>
}>()

const headers = [
    {title : "Событие",sortable: false, key: 'event', align: 'start' },
    {title : "Ресурс",sortable: false, key: 'resource', align: 'start' },
    {title : "Инициатор",sortable: false, key: 'actor.fullName', align: 'start' },
    {title : "Контекст",sortable: false, key: 'context', align: 'start' },
    {title : "Мета",sortable: false, key: 'meta', align: 'start' },
    {title : "Время и дата",sortable: false, key: 'createdAt', align: 'start' },
]

</script>

<template>
    <BasePaginatedTable
        :elements="activityLogs"
        :headers="headers"
        title="Логи"
    >
        <template #toolbar-left>
            
        </template>
        <template #item.context="{ item }">
            <DetailsDropdown>
                <BaseList>
                    <BaseListItem 
                        v-for="record in [item.context]"
                    >
                        <div class="whitespace-pre">
                            {{ record }}
                        </div>
                        
                    </BaseListItem>
                </BaseList>
                
            </DetailsDropdown>
        </template>

        <template #item.actor.fullName="{ item }">
            {{ item.actor?.fullName ?? item.actorType}}
        </template>

        <template #item.meta="{ item }">
            <DetailsDropdown>
                <BaseList>
                    <BaseListItem 
                        v-for="record in [item.meta]"
                    >
                        <div class="whitespace-pre">
                            {{ record }}
                        </div>
                        
                    </BaseListItem>
                </BaseList>
                
            </DetailsDropdown>
        </template>

        <template #item.createdAt="{ item }">
            {{ new DateFormatter(item.createdAt).format('H:i, d.m.Y') }}
        </template>
    </BasePaginatedTable>
</template>