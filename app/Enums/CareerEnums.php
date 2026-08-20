<?php

namespace App\Enums;

final class CareerEnums
{
    public const ROLES = ['SUPERADMIN', 'ADMIN', 'USER'];

    public const PERMISSIONS = [
        'manage_users', 'manage_admins', 'manage_roles', 'manage_permissions',
        'view_jobs', 'create_jobs', 'update_jobs', 'publish_jobs', 'close_jobs',
        'view_candidates', 'view_applications', 'manage_applications', 'change_application_status',
        'view_documents', 'verify_documents', 'download_documents',
        'manage_interviews', 'evaluate_interviews', 'manage_tests', 'manage_offers', 'manage_talent_pool',
        'view_reports', 'export_reports', 'manage_notifications', 'view_audit_logs', 'manage_master_data', 'manage_settings',
        'disc.bank.view', 'disc.bank.create', 'disc.bank.update', 'disc.bank.publish', 'disc.bank.archive',
        'disc.scoring.view', 'disc.scoring.manage',
        'disc.normalization.view', 'disc.normalization.manage',
        'disc.profile.view', 'disc.profile.manage',
        'disc.assessment.create', 'disc.assessment.manage',
        'disc.result.view', 'disc.result.export',
        'disc.security.monitor',
    ];

    public const DISC_BANK_STATUSES = ['DRAFT', 'REVIEW', 'PUBLISHED', 'ARCHIVED'];
    public const DISC_ASSESSMENT_STATUSES = ['DRAFT', 'PUBLISHED', 'CLOSED', 'ARCHIVED'];
    public const DISC_RESULT_VISIBILITIES = ['HRD_ONLY', 'PARTICIPANT_AFTER_COMPLETION', 'PARTICIPANT_AFTER_HRD_APPROVAL'];
    public const DISC_SESSION_STATUSES = ['INITIALIZED', 'IN_PROGRESS', 'COMPLETED', 'EXPIRED'];
    public const DISC_FACTORS = ['D', 'I', 'S', 'C', '*'];

    public const JOB_STATUSES = ['DRAFT', 'PUBLISHED', 'CLOSED', 'ARCHIVED'];

    public const APPLICATION_STATUSES = [
        'DRAFT', 'SUBMITTED', 'DOCUMENT_REVIEW', 'ADMINISTRATIVE_REVIEW', 'HR_INTERVIEW', 'USER_INTERVIEW',
        'TECHNICAL_TEST', 'FINAL_INTERVIEW', 'MEDICAL_CHECK_UP', 'OFFERING', 'HIRED', 'REJECTED', 'WITHDRAWN', 'CANCELLED',
    ];

    public const APPLICATION_TRANSITIONS = [
        'DRAFT' => ['SUBMITTED', 'CANCELLED'],
        'SUBMITTED' => ['DOCUMENT_REVIEW', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'DOCUMENT_REVIEW' => ['ADMINISTRATIVE_REVIEW', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'ADMINISTRATIVE_REVIEW' => ['HR_INTERVIEW', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'HR_INTERVIEW' => ['USER_INTERVIEW', 'TECHNICAL_TEST', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'USER_INTERVIEW' => ['TECHNICAL_TEST', 'FINAL_INTERVIEW', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'TECHNICAL_TEST' => ['FINAL_INTERVIEW', 'MEDICAL_CHECK_UP', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'FINAL_INTERVIEW' => ['MEDICAL_CHECK_UP', 'OFFERING', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'MEDICAL_CHECK_UP' => ['OFFERING', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'OFFERING' => ['HIRED', 'REJECTED', 'WITHDRAWN', 'CANCELLED'],
        'HIRED' => [],
        'REJECTED' => [],
        'WITHDRAWN' => [],
        'CANCELLED' => [],
    ];

    public const DOCUMENT_TYPES = ['CV', 'KTP', 'KK', 'IJAZAH', 'TRANSKRIP', 'PAS_FOTO', 'SKCK', 'SERTIFIKAT', 'SURAT_PENGALAMAN', 'PORTFOLIO', 'OTHER'];
    public const DOCUMENT_STATUSES = ['PENDING', 'VERIFIED', 'INVALID', 'NEED_REVISION'];
    public const QUESTION_TYPES = ['TEXT', 'TEXTAREA', 'NUMBER', 'YES_NO', 'SINGLE_SELECT', 'MULTI_SELECT', 'DATE'];
    public const INTERVIEW_STATUSES = ['SCHEDULED', 'CONFIRMED', 'COMPLETED', 'CANCELLED', 'RESCHEDULED', 'NO_SHOW'];
    public const OFFER_STATUSES = ['DRAFT', 'PREPARED', 'SENT', 'ACCEPTED', 'REJECTED', 'EXPIRED', 'CANCELLED'];
}
