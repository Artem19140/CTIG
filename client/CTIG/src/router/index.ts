import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/migrants',
      name: 'migrants',
      component:  () => import('@/pages/Migrants/Migrants.vue'),
      children: [
        {
          path: 'add',
          component: () => import('@/components/MigrantAddForm/MigrantAddForm.vue'),
        },
      ],
    },

    {
      path: '/exam',
      name: 'exam',
      component:  () => import('@/components/ExamCalendar/ExamCalendar.vue'),
      children: [
        {
          path: 'calendar',
          component: () => import('@/components/ExamCalendar/ExamCalendar.vue'),
        },
      ],
    },

    {
      path: '/tests',
      name: 'test',
      component:  () => import("@/pages/Tests/Tests.vue"),
      children: [
        {
          path: 'calendar',
          component: () => import('@/components/ExamCalendar/ExamCalendar.vue'),
        },
      ],
    },

    {
      path: '/examEntrance', //exam/examEntrance
      name: 'examEntrance',
      component:  () => import("@/pages/Exam/ExamEntrance.vue"),
    },

    {
      path: '/exams', //exam/lists/{id}
      name: 'examEntrance',
      component:  () => import("@/pages/Exam/ExamList.vue"),
    },

    {
      path: '/examming',
      name: 'examming',
      component:  () => import("@/pages/Exam/Exam.vue"),
    },

    {
      path: '/documents',
      name: 'documents',
      component:  () => import("@/pages/Documents/Documents.vue"),
    },
  ],
})

export default router
