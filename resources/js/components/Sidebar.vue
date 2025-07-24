<template>
  <aside class="sidebar">
    <nav>
      <div class="nav-header">NAVIGATION</div>
      <SidebarLink to="/dashboard" icon="tachometer-alt" label="Dashboard" />
      <SidebarCollapsible icon="ticket-alt" label="Tickets">
        <SidebarLink to="/tickets/mine" label="Assigned to Me" />
        <SidebarLink to="/tickets/pending" label="Pending" />
        <SidebarLink to="/tickets/in-progress" label="In Progress" />
        <SidebarLink to="/tickets/resolved" label="Resolved" />
        <SidebarLink to="/tickets/unassigned" label="Unassigned" />
      </SidebarCollapsible>
      <SidebarCollapsible icon="edit" label="Content">
        <SidebarLink to="/blogs" label="Blog" />
        <SidebarLink to="/services" label="Services" />
        <SidebarLink to="/faqs" label="FAQs" />
      </SidebarCollapsible>
      <SidebarLink to="/newsletter" icon="envelope" label="Newsletter" />
      <SidebarLink to="/contacts" icon="address-book" label="Contacts" />
      <SidebarLink to="/users" icon="users" label="Users" />
      <SidebarCollapsible icon="briefcase" label="Call Log (Jobs)">
        <SidebarLink to="/jobs/my-jobs" label="My Jobs" />
        <SidebarLink to="/jobs/in-progress" label="In Progress" />
        <SidebarLink to="/jobs/completed" label="Completed" />
        <SidebarLink to="/jobs/pending" label="Pending" />
        <SidebarLink to="/jobs/unassigned" label="Unassigned" />
        <SidebarLink to="/jobs/all" label="All Jobs" />
      </SidebarCollapsible>
      <SidebarLink to="/report" icon="chart-line" label="Report" />
      <SidebarLink to="/tickets-report" icon="file-alt" label="Tickets Report" />
    </nav>
  </aside>
</template>

<script setup>
import SidebarLink from './SidebarLink.vue'
import SidebarCollapsible from './SidebarCollapsible.vue'
</script>

<style scoped>
.sidebar {
  width: var(--sidebar-width);
  background: var(--sidebar-bg);
  color: var(--sidebar-text);
  position: fixed;
  top: var(--navbar-height);
  left: 0;
  height: calc(100vh - var(--navbar-height));
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  display: flex;
  flex-direction: column;
  z-index: 1020;
  border-right: 1px solid #e5e7eb;
}
.nav-header {
  padding: 1rem 1.5rem 0.5rem;
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--sidebar-text);
  letter-spacing: 1px;
}
</style>